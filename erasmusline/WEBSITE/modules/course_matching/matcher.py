# -*- coding: cp1252 -*-
import math
import nltk
import _mysql
import MySQLdb
import string
import sys

ectsPenalty = 0.1;
db = MySQLdb.connect(host='localhost', user='erasmusline', passwd='orange', db='erasmusline')
cursor = db.cursor()

def textItem(cont, title, cid, ects):
    return (nltk.Text(clean_tokens(nltk.wordpunct_tokenize(cont)), title), cid, ects)

def cosine_distance(a, b):
    if len(a) != len(b):
        return False
    numerator = 0
    denoma = 0
    denomb = 0
    for i in range(len(a)):
        numerator += a[i]*b[i]
        denoma += abs(a[i])**2
        denomb += abs(b[i])**2
    if (denoma > 0 and denomb > 0):
        result = 1 - numerator / (math.sqrt(denoma)*math.sqrt(denomb))
    else:
        result = 1;
    return result

def text_simi(t1, t2, coll):
    tf1 = []
    tf2 = []
    i = 0
    for t in t1.tokens:
        o1 = coll.tf_idf(t, t1)
        if o1 > 0:
            tf1.append(o1)
            tf2.append(coll.tf_idf(t, t2))
    return 1-cosine_distance(tf1, tf2)

def ects_simi(ects1, ects2):
    return math.fabs(int(ects1)-int(ects2));

def clean_tokens(tokens):
    ret = []
    for t in tokens:
        if (len(t) > 2):
            ret.append(t.lower())
    return ret

def resultItem(score, title, description, cid, ects):
    return str(cid) + "|" + str(score);#{'score': score, 'title': title, 'description': description, 'cid': cid, 'ects': ects}

def find_simis(t, texts, coll):
    ret = []
    for tc in texts:
        if (tc != t):
            ret.append((tc, (text_simi(t[0], tc[0], coll) - ects_simi(t[2], tc[2])*ectsPenalty)))
    ret = sorted(ret, key=lambda text: text[1])
    ret.reverse()

    trueret = [resultItem(1.0, t[0].name, string.joinfields(t[0].tokens), t[1], t[2])]
    for r in ret:
        trueret.append(resultItem(r[1], r[0][0].name, string.joinfields(r[0][0].tokens), r[0][1], r[0][2]))

    trueret = trueret[0:11]
    stringret = string.split(trueret[0], '|')[0];
    
    for s in trueret[1:11]:
        stringret += "," + str.split(s, '|')[0];
    stringret += "|" + string.split(trueret[0], '|')[1];

    for s in trueret[1:11]:
        stringret += "," + str.split(s, '|')[1];
    
    return stringret
    

def getCoursesForInst(instId, skip):
    cursor.execute("SELECT courseId, courseName, courseDescription, ectsCredits FROM coursespereducperinst AS cei INNER JOIN institutions AS i ON cei.institutionId = i.instEmail WHERE i.instId = " + str(instId) + " and cei.courseId != " + str(skip));
    result = cursor.fetchall()
    texts = []
    for itm in result:
        texts.append(textItem(itm[2], itm[1], itm[0], itm[3]))
    return texts

def getCourseById(courseId):
    cursor.execute("SELECT courseId, courseName, courseDescription, ectsCredits FROM coursespereducperinst AS cei WHERE courseId = " + str(courseId))
    if (cursor.rowcount == 1):
        res = cursor.fetchone()
        return textItem(res[2], res[1], res[0], res[3])

def matchCourseIDs(courseToMatchId, instForMatchingId):
    mainText = getCourseById(courseToMatchId)
    if (mainText != None):
        matchCourse(mainText, instForMatchingId)

def matchCourse(courseToMatchItem, instForMatchingId):
    texts = getCoursesForInst(instForMatchingId, courseToMatchItem[2])
    if (len(texts) > 0):
        itms = []
        for t in texts:
            itms.append(t[0])
        coll = nltk.TextCollection(itms)
        titms = find_simis(courseToMatchItem, texts, coll);
        print titms

if (sys.argv[1] == "ids"):
    matchCourseIDs(sys.argv[2], sys.argv[3])
elif (sys.argv[1] == "spec"):
    course = textItem(sys.argv[2], sys.argv[3], -1, sys.argv[4])
    matchCourse(course, sys.argv[5])

# Call this script like this when you have a course id:
# matcher.py ids originalCourseId targetInstitutionId
# For freeform mathing to a course not in the database
# matcher.py spec "description" "name" ectsCredits instId

#Note: to run this you must install Python, the Python NLTK (and therefore also PyYAML) and the python MySQLdb plugin
#URLs for these: http://python.org, http://www.nltk.org and http://mysql-python.sourceforge.net/
#Make sure that you get Python 2.5, this is written for that version (2.6 might work though, haven't tested that)
