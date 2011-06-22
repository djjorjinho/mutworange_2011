
<div class="mainDiv">
    <h3>Welcome Erasmus Coordinator</h3>

    <fieldset>
        <legend>Your forms</legend>
    <ul>
    <li class="level2"><a href="index.php?module=staff&amp;view=precandidates" title="Show Precandidate Forms">Show Precandidate Forms</a></li>
    <li class="level2"><a href="index.php?module=staff&amp;view=applics" title="Show Student Application Forms">Show Student Application Forms</a></li>
    <li class="level2"><a href="index.php?module=staff&amp;view=agreements" title="Show Learning Agreements">Show Learning Agreements</a></li>
    <li class="level2"><a href="index.php?module=staff&amp;view=changes" title="Show Change of Learning Agreements">Show Change of Learning Agreements</a></li>
    <li class="level2"><a href="index.php?module=trrec&amp;view=select">Show Transcript of Records</a></li>
    <li class="level2"><a href="index.php?module=abroad_stay&amp;view=select">Show Certificates</a></li>
    <li class="level2"><a href="index.php?module=staff&amp;view=applics" title="Students">Students</a></li>
</ul>
</fieldset>
    <fieldset>
    <legend>Manage exams</legend>
    <div class="radioExams">
{$exams}
        </div>
    </fieldset>
    
    <fieldset>
    <legend>Notifications of your latest events</legend>
<ul>
{iteration:iEvents}
{$event}
{/iteration:iEvents}
    </ul>
</fieldset>	
    
</div>    