<?php

class Course_matchingDB {
    public static function getInstitutions() {
		$db = PlonkWebsite::getDB();
		
		$items = $db->retrieve("select instName, instId from institutions");
		
		return $items;
	}
	
	public static function getCourses() {
		$db = PlonkWebsite::getDB();
		
		$items = $db->retrieve("select cpe.courseName, cpe.ectsCredits, i.instName, cpe.courseId from coursespereducperinst as cpe inner join institutions as i on cpe.institutionId = i.instEmail order by i.instName, cpe.courseName");
		
		return $items;
	}
	
	public static function getCoursesForIDs($ids) {
		$db = PlonkWebsite::getDB();
		
		$items = $db->retrieve("select * from coursespereducperinst where courseId in (" . $db->escape($ids) . ") order by field (courseId, " . $db->escape($ids) . ");");
		
		return $items;
	}
}
