@extends('layouts.default')

@section('content')
<h4>News</h4>

<div class="well">
    <strong>03/05/2014:</strong><br>
    Fixed bug if you clicked on a user in the users lists that haven't registered anything.<br>
    Fixed a permission issue causing an error 500.<br>
    <br>
    As a side note, we have a few people not activated yet, if you know any of them, please have them try and log in again. Once logged in there should be a link to resend the activation mail.
    If this doesn't work, feel free to whisper or mail me ingame.
    <br>
    <strong>25/04/2014:</strong><br>
    Change Lab 3 experiment description to "Top 10 adventures for loot by amount".<br>
    Added "Top 10 adventures for loot by dropchance" to Lab 3 by request from MarkG.<br>
    Changed handling on clicking a users name on /users/ from redirecting people to /latest/{username} to /stats/personal/{username}, this should give a little more info on other users progress.
    Thanks to Notious for the idea.<br>
    <br>
    <strong>18/04/2014:</strong><br>
    Labs are back!
    We're testing a new feature to see the best adventure to play for X drop, go have a look and tell me if the math is off.<br>
    <br>
    <strong>29/03/2014:</strong><br>
    Personal stats have been updated with an option to filter on dates under "Accumulated Loot" and "Adventures Played", please leave your feedback on
    feature.<br>
    Made columns in the user list sortable by request from JayUK.<br>
    Added option to delete a submission.<br>
    Added some general stats under personal.
    Added a "total registered" count to both global and personal stats.
    <br>
    <strong>23/03/2014:</strong><br>
    Fixed error code 500 bug at signup, thanks Jay.<br>
    <br>
    <strong>15/03/2014:</strong><br>
    Fixed bug when clicking a players named from Users<br>
    <br>
    <strong>13/03/2014:</strong><br>
    I've done a major refactoring of the site, expect some bugs here and there. As always, please mail me (Procat) ingame with your feedback.<br>
    Next up will be some added info on the personal stats page, more on that soon :)<br>
    <br>
    <strong>13/03/2014:</strong><br>
    I've done a major refactoring of the site, expect some bugs here and there. As always, please mail me (Procat) ingame with your feedback.<br>
    Next up will be some added info on the personal stats page, more on that soon :)<br>
    <br>
    Changes:<br>
    Moved "register" to a button under login.<br>
    Changed layout on latest after suggestions from JayUK.<br>
    Added new personal stats page.<br>
    Code cleanup, stats pages should load a bit faster now.<br>
    Added delete button to loot under Personal Stats - Latest.<br>
    <br>
    <strong>03/03/2014:</strong><br>
    Fixed issue with some double loot on CLT, thanks to MarkG for reporting.<br>
    Closed Lab for the time being and promoted experiment 2 to the new default.<br>
    Changed "Register loot" to "Add loot".<br>
    Addded some caching to help speed things up.<br>
    <br>
    <strong>16/02/2014:</strong><br>
    Added "Loot added" to users.<br>
    Minor changes to various loot options on Bandit, Secluded Experiments and Horseback<br>
    <br>
    <strong>16/02/2014:</strong><br>
    Fixed various bugs with resetting password and resending activation code.<br>
    <br>
    <strong>10/02/2014:</strong><br>
    Made some changes to labs and added a second experiment based on feedback from the first.<br>
    <br>
    <strong>08/02/2014:</strong><br>
    Added a new Lab section for trying out alternative ways of adding loot, please give me your feedback ingame.<br>
    Added personal stats, click "Stats" in the menu to see it.<br>
    <br>
    <strong>27/01/2014:</strong><br>
    Added an users page.<br>
    Reorganized the menu a bit.<br>
    Minor layout change on overview for each loot table.<br>
    Minor code cleanup.<br>
    <br>
    <strong>25/01/2014:</strong><br>
    Added Invasion of the Nords (Nords 2).<br>
    Fixed typo on Return to Bandit Nest.<br>
    Fixed issues with theme selection not saving correctly.<br>
    If there is only one type of loot available in a slot, it'll now be selected by default.<br>
    <br>
    <strong>22/01/2014:</strong><br>
    Added some more themes.<br>
    <br>
    <strong>12/01/2014:</strong><br>
    Fixed issue on overview with the top tables not splitting adventures correctly between the 2 tables.<br>
    Disabled debugging on production site.<br>
    Minor tweaks to login system.<br>
</div>

<h4>About</h4>

<div class="well">
    So what is this? The LM Loot Tracker is an evolution of an old system originally made while I was part of MI5 in an effort to check if various reported
    loot results from adventures were accurate. The <a href="http://lt.mi5guild.com">MI5 Loot Tracker</a>, while made to double check loot drop accuracy, later turned into a site members used
    to check their own progress, the site had over 6000 submissions around the time I left the guild.<br>
    The new LM Loot Tracker builds upon the code from the old system and adding new features, fixing bugs and generally better to work with.  The site is
    free for all to use, comments, suggestions and other feedback can be directed towards Procat on Newfoundland either in mail or whisper.
</div>
@stop