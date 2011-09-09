<?php
/**
 * ExoBase ORM Tester
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */

class ORM_Tester extends Exo_Controller
{
    public function doc()
    {
?>
<!DOCTYPE html>
<html>
    <head>
        <title>ExoBase ORM</title>
    </head>
    <body>

<h1 id="title">ExoBase Object Relational Model (ORM)</h1>
<p>Database storage using PHP Objects</p>
<p><a href="http://www.exoduslabs.ca/">Return to Exodus Labs</a></p>

<h2 id="toc">Table of Contents</h2>
<ol>
    <li><a href="toc">Table of Contents</a></li>
    <li><a href="#idea">Idea</a></li>
    <li><a href="#pseudo">Examples or Pseudo-Code</a></li>
    <li><a href="#xml">Structure Definition XML</a></li>
    <li><a href="#goals">Current Goals</a></li>
    <li><a href="#future">Future Goals</a></li>
    <li><a href="#status">Current Status</a></li>
</ol>

<h2 id="idea">Idea</h2>
<p>Provide project designers and developers with a quicker way of developing their applications without having to worry about the database they're using, designing of tables, or optimizing of indexes and queries.  Allow the designing of the data via XML (or other formats, eventally) and the complex/simple relationships between the data, then easily retrieve, modify, and delete the data appropriately.</p>

<h2 id="pseudo">Examples or Pseudo-Code</h2>
<p>All examples are assumed to be preceded with some kind of initialization of the ExoBase object, something like:</p>
<pre>
$base = new ExoBase('path/to/config.xml');
</pre>
<p>or</p>
<pre>
$base = new ExoBase(array(
    'config' => 'path/to/config.xml',
    'option' => 'value'
));
</pre>

<h3>Getting a Record</h3>
<pre>
$base->users(array(
    'where' => array('id' => 1), 
    'limit' => 1
));
</pre>

<h3>Getting Multiple Records</h3>
<pre>
$base->users(array(
    'where' => array('name', 'like', 'G%')
));
</pre>

<h3>IN-List Retrieval</h3>
<pre>
$base->users(array(
    'where' => array('name' => array('Guillaume', 'Kevin', 'Paige'))
));
</pre>

<h3>Traverse a Relationship</h3>
<pre>
$user = $base->users(array(
    'where' => array('id' => 1),
    'limit' => 1
));
$posts = $user->posts(array(
    'limit' => 3,
    'order' => array('date_created' => 'asc')
);
</pre>

<h3>Manipulation</h3>
<pre>
$user = $base->users(array(
    'where' => array('id' => 1),
    'limit' => 1
));
$user->first_name = 'Guillaume';
$base->_save($user); 
// OR 
$user->_save();
</pre>

<h3>Destruction</h3>
<pre>
$user = $base->users(array(
    'where' => array('id' => 1),
    'limit' => 1
));
$user->_destroy();

// OR ALTERNATIVELY, allowing for more sweeping statements...

$base->_destroy('users', array(
    'where' => array('id' => 1),
    'limit' => 1
));
</pre>

<h3>Complex Mapping of Type-Based Junction Table</h3>
<p>This case will assume that there is a forum allowing posting of a thread, poll, or image.</p>
<pre>
// as a special case, asking for a specific ID will return one result
$forum = $base->forums(array('where' => array('id' => 3)));

// traverse to the "posts" which can be multiple things, defined by "type"
$posts = $forum->posts;

// now go through them
foreach ($posts as $post)
{
    switch ($post->type)
    {
        case 'thread':
            print('This is a thread.&lt;br /&gt;');
            var_dump($post->replies);
            break;

        case 'poll':
            print('This is a poll.&lt;br /&gt;');
            var_dump($post->options);
            break;

        case 'image':
            printf('This is an image. &lt;img src="%s" alt="%s" /&gt;&lt;br /&gt;',
                $post->src,
                $post->title
            );
            break;

        default:
            print('Invalid post reached, but this "should" never happen.');
            break;
    }
}
</pre>

<h3>More Examples Coming...</h3>

<p>Content coming..</p>

<h2 id="xml">XML Structure Example</h2>
<pre>
&lt;?xml version="1.0" encoding="UTF-8" ?&gt;
&lt;entities&gt;
    &lt;entity name="people"&gt;
        &lt;attributes&gt;
            &lt;attribute name="name" type="string" searchable="true" /&gt;
            &lt;attribute name="email" type="string" searchable="true" validation="email" /&gt;
        &lt;/attributes&gt;
        &lt;relationships&gt;
            &lt;has-many entity="posts" /&gt;
        &lt;/relationships&gt;
    &lt;/entity&gt;
    &lt;entity name="forums"&gt;
        &lt;attributes&gt;
            &lt;attribute name="title" type="string" /&gt;
            &lt;attribute name="body" type="text" /&gt;
        &lt;/attributes&gt;
        &lt;relationships&gt;
            &lt;has-many entity="posts" /&gt;
        &lt;/relationships&gt;
    &lt;/entity&gt;
    &lt;entity name="posts"&gt;
        &lt;attributes&gt;
            &lt;attribute name="title" type="string" /&gt;
            &lt;attribute name="body" type="text" /&gt;
        &lt;/attributes&gt;
        &lt;relationships&gt;
            &lt;has-a name="author" entity="people" /&gt;
        &lt;/relationships&gt;
    &lt;/entity&gt;
&lt;/entities&gt;
</pre>

<h2 id="goals">Current Goals</h2>
<ul>
    <li>Database-neutral, support MySQL, PostgreSQL, MongoDB, maybe even XML (slow...) storage?</li>
    <li>Allow quicker development by designing the data, and just using it from there</li>
    <li>Make implementation quick: include library, load structure file, and go!</li>
    <li>Traverse data via relationships very quickly, user -&gt; friend &gt; posts &gt; replies &gt; etc.</li>
    <li>Make use of efficiencies such as indexing where applicable</li>
</ul>

<h2 id="future">Future Goals</h2>
<ul>
    <li>Be as efficient as possible, looking at the "Unit Of Work" pattern</li> 
</ul>

<!-- Analytics Code Here IN CASE Testing Below Breaks It -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-7984873-24']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<h2 id="status">Current Status</h2>
<p>This section will have a table of the current status of the ORM design and implementation, to show current status.  It's our hope that this will have a pretty good implementation created by end of 2011.</p>
<p><strong>Alpha</strong></p>
<p><?= link_to('orm_test', 'Click Here to View Current Test Results'); ?></p>

    </body>
</html>

<?php
    }

    public function test()
    {
        ?>
<!DOCTYPE html>
<html>
    <head>
        <title>ExoBase ORM Test</title>
    </head>
    <body>
        <!-- Analytics Code Here IN CASE Testing Below Breaks It -->
        <script type="text/javascript">

          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-7984873-24']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();

        </script>
        <?php

        set_time_limit(5); // currently only allow 5 seconds execution
        ini_set('display_errors', true);
        error_reporting(E_ALL | E_NOTICE);

        $start = microtime(TRUE);

        $test = new Exo_Test(array('verbose' => FALSE, 'summary' => TRUE));

        print("<h1>ExoBase ORM Test</h1>");
        print("<pre>");

        // Start up
            $orm = new ExoBase_ORM('example.xml');
            $test->assert('Start the ORM', $orm != FALSE, array('fatal' => TRUE)); 

            $person = $orm->people(1);
            $test->assert('Grab the person with ID 1 and get a container, via "$orm->people(1);"', $person instanceof ExoBase_Record);

            $test->assert('Person has ID 1', $person->id == 1);
            $test->assert('Person is Gui', $person->name == 'Gui');

            $person->name = 'Guillaume';
            $test->assert('Name changed to "Guillaume"', $person->name == 'Guillaume');

            $result = $person->_save();
            $test->assert('Save name change via record', $result == TRUE);

            $new_person = $orm->people(1);
            $test->assert('Name change has been verified to be saved', $new_person->name == 'Guillaume');

            $person->name = 'Gui';
            $person->_save();
            $test->assert('Name changed back to Gui by saving', $person->name == 'Gui');

            $person->name = 'Guillermo'; 
            $person->_revert();
            $test->assert('Name changed to "Guillermo" temporarily, then "$person->_revert()"ed back to Gui', $person->name == 'Gui');
            
            // Pivotal #15633197 - When an entity attribute or relationship doesn't exist when requested, throw an exception
            $success = FALSE;
            $fake_entity = 'j3g45jkh34g';
            try { $orm->$fake_entity; } catch (ExoBase_Exception $e) { $success = ($e->getMessage() == ('The requested entity "' . $fake_entity . '" is not defined')); }
            $test->assert('When an entity doesn\'t exist, throw an exception', $success);
            $success = FALSE;
            $fake_attribute = 'j3g45jkh34g';
            $entity_name = $person->_entity->name;
            try { $person->$fake_attribute; } catch (ExoBase_Exception $e) { $success = ($e->getMessage() == (sprintf('Invalid attribute "%s" requested from entity "%s"', $fake_attribute, $entity_name))); }
            $test->assert('When an entity attribute or relationship doesn\'t exist, throw an exception', $success);
            // End of Pivotal #15633197

            $friends = $person->friends;
            $test->assert('Simple traverse to "friends" relationship of Gui returning a container with two people in it', ($friends instanceof ExoBase_Container) && count($friends) == 2);

            $test->assert('First friend is Kevin', $friends[0]->name == 'Kevin');
            $test->assert('Second friend is Steve', $friends[1]->name == 'Steve');

            $steves_friends = $friends[1]->friends;
            $test->assert('Steve is his own friend, traverse once and verify it\'s Steve', $steves_friends[0]->name == 'Steve');
            for ($x = 0; $x < 3; $x++)
            {
                $self = $steves_friends[0]->friends[0];
                $test->assert('.. traverse #' . ($x + 2) . ' is Steve again', $self->name == 'Steve');
            }

            $gui = $orm->employees(1);
            $test->assert('Fetch Gui as an employee', $gui->name == 'Gui'); 
            $test->assert('Gui has a department of Development', $gui->department == 'Development');
            $test->assert('Gui does not have a boss', $gui->boss === NULL);

            // Pivotal Tracker Story 15393913 - test that a relationship from an extended entity can be accessed
            $friends = $gui->friends;
            $test->assert('Employee Gui\'s first friend is Kevin, proving extended entity traversal of extension', $friends[0]->name == 'Kevin');
            $test->assert('Employee Gui\'s second friend is Steve', $friends[1]->name == 'Steve');
            // End of Pivotal Tracker Story 15393913
            
            $kevin = $orm->employees(2);
            $test->assert('Fetch Kevin as an employee', $kevin->name == 'Kevin');
            $test->assert('Kevin has a department of Communications', $kevin->department == 'Communications');
            $test->assert('Kevin has Gui as a boss', $kevin->boss->name == 'Gui');

            $gui_data = array(
                'email' => 'guinew@exodusmedia.ca',
                'name' => 'Guillermo VanderExt'
            );
            $gui->_merge($gui_data);
            $test->assert('Gui\'s data is merged with an array, changing his name and email', $gui->name == 'Guillermo VanderExt' && $gui->email == 'guinew@exodusmedia.ca');

            $gui_object = new stdClass();
            $gui_object->name = 'Guigui VanderTest';
            $gui_object->email = 'guigui@exodusmedia.ca';
            $gui->_merge($gui_object);
            $test->assert('Gui\'s data is merged with an object, changing his name and email again', $gui->name == 'Guigui VanderTest' && $gui->email == 'guigui@exodusmedia.ca');

            $kevin->_merge($gui);
            $test->assert('Kevin has merged with Gui, overtaking Kevin\'s data (checking name and email)', $kevin->name == $gui->name && $kevin->email == $gui->email);

            $test->assert('Reverted the merger, returning him to being Kevin', $kevin->_revert() && $kevin->name == 'Kevin' && $kevin->email == 'kevin@exodusmedia.ca');
            $gui->_revert();
            $test->assert('Reverted Gui too', $gui->name == 'Gui' && $gui->email == 'gui@exodusmedia.ca');

            // test andrew can be added to my friends
            $gui = $orm->people(1);
            $friends = $gui->friends;

            $andrew = $orm->people->_new();
            $date_created = $andrew->date_created;
            $date_updated = $andrew->date_updated;
            $test->assert('Create a new people entity', $andrew instanceof ExoBase_Record);
            $test->assert('Verify it does not have an ID, as it has not yet been saved', $andrew->id === NULL);
            $andrew->name = 'Andrew';
            $andrew->email = 'andrew@exodusmedia.ca';
            $test->assert('Unsaved entity retains the name and email address assigned to it', $andrew->name == 'Andrew' && $andrew->email == 'andrew@exodusmedia.ca');
            //$andrew->id = 'test';
            //$test->assert('Entity does not allow setting of ID field', $andrew->id === NULL);
            //$andrew->date_created = 'test';
            //$test->assert('Entity does not allow setting of date_created field', $andrew->date_created == $date_created);
            //$andrew->date_updated = 'test';
            //$test->assert('Entity does not allow setting of date_updated field', $andrew->date_updated == $date_updated);
            $test->assert('Saving of Andrew is possible', $andrew->_save());
            $test->assert('Saved Andrew now has an ID and date_created and date_updated', $andrew->id !== NULL && $andrew->date_created !== NULL && $andrew->date_updated !== NULL);
            $test->assert('Destruction action of entity is invokable', $andrew->_destroy());
            $test->assert('Andrew is destroyed', $andrew->id === NULL);

            $brenden = $orm->employees->_new();
            $test->assert('Brenden can be created as an employee', $brenden instanceof ExoBase_Record);
            $brenden->name = 'Brenden';
            $brenden->email = 'brenden@exodusmedia.ca';
            $brenden->department = 'Development';
            $test->assert('Brenden can be saved to ORM', $brenden->_save() && $brenden->id !== NULL);
            $verify = $orm->employees($brenden->id);
            $test->assert('Brenden (newly saved) is retrievable from ORM', $verify->id == $brenden->id && $verify->name == $brenden->name && $verify->email == $brenden->email && $verify->department == $brenden->department);

            // cloning tests
            $clone = $brenden->_clone();
            $test->assert('Brenden can be cloned', $clone instanceof ExoBase_Record);
            $test->assert('Cloned object will have no ID and creation/update dates will not be the same as original', $clone->id === NULL);

            // destruction tests
            $brenden_id = $brenden->id;
            $test->assert('Employee can be destroyed', $brenden->_destroy());
            $verify = $orm->employees($brenden_id);
            $test->assert('Employee is verified to be destroyed', $verify === NULL);
            $provided = $orm->employees->_new(array('name' => 'Provided Name', 'email' => 'provided@email.com'));

            // if there are residuals from previously failed tests (at this point, Brendens or Andrews) delete them too
            $brenden->_save();
            $residuals = $orm->people(
                array('where' => array(
                    array('name', 'starts_with', 'Brenden', 'Andrew')
                ))
            );
            $test->assert('If there are any residual employees from failed tests, destroy them', $residuals->_destroy());
            $test->assert('Verify they were destroyed', count($residuals) == 0);

            $gui = $orm->people(1);
            $date_created = $gui->date_created;
            $date_updated = $gui->date_updated;
            $test->assert('Gui has a date_created that is numeric and greater than zero', $gui->date_created > 0 && is_numeric($gui->date_created));
            $test->assert('Gui has a date_updated that is numeric and greater than zero', $gui->date_updated > 0 && is_numeric($gui->date_updated));
            $test->assert('Gui can be saved two seconds later', sleep(2) == 0 && $gui->_save(), array('omit' => TRUE));
            $gui = $orm->people(1);
            $test->assert('After sleeping one second, saving, and re-fetching, verify the date_created remains unchanged', ($gui->date_created == $date_created));
            $test->assert('After sleeping one second, saving, and re-fetching, verify the date_updated is more than second larger', ($gui->date_updated > ($date_updated + 1)));


            $new_employee = $orm->employees->_new(array(
                'name' => 'Snape',
                'email' => 'severus@snape.com',
                'department' => 'Defense Against the Dark Arts'
            ));
            $test->assert('Create a new employee named Snape using an array as initial data', $new_employee->_save());
            $test->assert('Verify Snape has an ID', $new_employee->id !== NULL);
            $test->assert('Verify Snape can be retrieved', $orm->employees($new_employee->id)->id == $new_employee->id);

            $test->assert('Verify there is no existing hero using this ID', $orm->heroes($new_employee->id) === NULL);
            $new_hero = $orm->heroes->_new($new_employee);
            $test->assert('Create a new hero entity using the same Snape employee record', $new_hero instanceof ExoBase_Record);
            $new_hero->power = 'Heart';
            $test->assert('Hero Snape is savable', $new_hero->_save());
            $test->assert('Newly saved Hero Snape has an ID', is_numeric($new_hero->id));
            $hero = $orm->heroes($new_hero->id);
            $test->assert('Hero Snape is retrievable', $hero instanceof ExoBase_Record && $hero->id == $new_hero->id);
            
            $snape = $orm->people($new_hero->id);

            $test->assert('The Person Snape can be retrieved using the same ID and they all match up', $snape->id == $new_hero->id && $new_hero->id == $new_employee->id);
            $test->assert('The Hero Snape can be destroyed using OBLITERATE mode', $new_hero->_destroy(TRUE));
            $test->assert('The Person and Employee Snapes should also be destroyed', $orm->people($snape->id) === NULL && $orm->employees($snape->id) === NULL);

            $new_employee = $orm->employees->_new(array(
                'name' => 'Snape',
                'email' => 'severus@snape.com',
                'department' => 'Defense Against the Dark Arts'
            ));
            $new_employee->_save();
            $new_hero = $orm->heroes->_new($new_employee);
            $new_hero->power = 'Heart';
            $new_hero->_save();
            $test->assert('Recreating the same scenario, destroying a Hero Snape entity using ENTITY mode', $new_hero->_destroy(FALSE));
            $test->assert('Employee and Person Snape should both be retrievable', $orm->employees($new_employee->id) instanceof ExoBase_Record && $orm->people($new_employee->id) instanceof ExoBase_Record);

            $snape_id = $new_employee->id;
            $snape = $orm->people($snape_id);
            $test->assert('Destroying the Person Snape should destroy all extending entities, regardless of OBLITERATE mode', $snape->_destroy());
            $test->assert('Snape Employee should not be retrievable, as Person Snape no longer exists', $orm->employees($snape_id) === NULL);

            // Pivotal #15548587 - give relationships the ability to have fields, which can be stored into
            $movie = $orm->movies(1);
            $test->assert('Movie \'The Hobbit\' can be retrieved', $movie instanceof ExoBase_Record && $movie->title == 'The Hobbit');
            $cast = $movie->cast;
            $test->assert('The movie\'s cast can be retrieved and exists', $cast instanceof ExoBase_Container && count($cast) > 0);
            $kevin = $cast[0];
            $test->assert('The first cast member is Kevin', $kevin->name == 'Kevin');
            $test->assert('Kevin has the role of "actor" (from relationship)', $kevin->role == 'actor');
            $kevin->role = 'director';
            $test->assert('Kevin\'s role can be changed to "director"', $kevin->role == 'director');
            $test->assert('Kevin can be saved as an director', $kevin->_save());
            $cast = $movie->cast;
            $kevin = $cast[0];
            $test->assert('Verify by retrieval that Kevin is a "director"', $kevin->role == 'director');
            $kevin->role = 'actor';
            $kevin->_save();
            $cast = $movie->cast;
            $kevin = $cast[0];
            $test->assert('And back to "actor"', $kevin->role == 'actor');
            
            // link and unlink tests
            $gui = $orm->people(1);
            $paige = $orm->people(4);
            $test->assert('Paige can be added to Gui\'s friends', $gui->friends->_link($paige)); // add paige
            $friends = $gui->friends;
            try { $friend3 = $gui->friends[2]; } catch (ExoBase_Exception $e) { $friend3 = NULL; }
            $test->assert('On fetch of Gui\'s friends, Paige should be the third entry', $friend3 instanceof ExoBase_Record && $friend3->name == 'Paige');
            $test->assert('Paige can be unlinked from the friends', $friend3 instanceof ExoBase_Record && $friend3->_unlink());
            $friends = $gui->friends;
            $success = FALSE; try { $friend3 = $gui->friends[2]; } catch (ExoBase_Exception $e) { $success = TRUE; }
            $test->assert('On re-fetch, Paige should no longer be the third entry (should throw exception)', $success);
            $test->assert('Alternate method of linking via record', $paige instanceof ExoBase_Record && $paige->_link($friends));
            $friends = $gui->friends;
            try { $friend3 = $gui->friends[2]; } catch (ExoBase_Exception $e) { $friend3 = NULL; }
            $test->assert('On fetch of Gui\'s friends, Paige should be the third entry', $friend3 instanceof ExoBase_Record && $friend3->name == 'Paige');
            $test->assert('Paige can be unlinked from the friends again', $friend3 instanceof ExoBase_Record && $friend3->_unlink());

            $success = FALSE; try { $friend3 = $gui->friends[2]; } catch (ExoBase_Exception $e) { $success = TRUE; }
            $test->assert('Since she in unlinked (and should be removed from container), trying to fetch Paige should give an exception', $success);

            $success = FALSE; try { $friend3 = $gui->friends[2]; } catch (ExoBase_Exception $e) { $success = TRUE; }
            $test->assert('On re-fetch, Paige should no longer be the third entry (should throw exception)', $success);

            // Pivotal #15580631 - allowing $container[] to link a record to a container
            $success = TRUE;
            try { $friends[] = $paige; } catch (ExoBase_Exception $e) { $success = FALSE; }
            $test->assert('Paige can be added via $friends[] = $paige', $success);
            $test->assert('Paige can be unlinked via $friends[count($friends)-1]->_unlink();', $friends[count($friends)-1]->_unlink());

            // Test _is_a() method to verify TRUE and FALSE result
            $gui = $orm->people(1);
            $test->assert('Gui as a person is a people entity', $gui->_is_a('people'));
            $kevin = $orm->employees(2);
            $test->assert('Kevin as an employee is a people entity', $kevin->_is_a('people'));
            $test->assert('Gui as a person is not an employee', !$gui->_is_a('employees'));

            // Test creation of an entity based on an array
            $new_person = $orm->people->_new(array('name' => 'New Guy', 'email' => 'new@guy.com'));
            $test->assert('New guy is created using an array', $new_person instanceof ExoBase_Record);
            $test->assert('New guy can be saved', $new_person instanceof ExoBase_Record && $new_person->_save());
            $test->assert('New guy has an id', $new_person instanceof ExoBase_Record && $new_person->id !== NULL);
            $fetch_new_guy = $orm->people($new_person->id);
            $test->assert('New guy can be retrieved', $fetch_new_guy instanceof ExoBase_Record && $fetch_new_guy->id == $new_person->id && $fetch_new_guy->name == $new_person->name && $fetch_new_guy->email == $new_person->email);
            $test->assert('New guy can be destroyed', $new_person->_destroy());
            $test->assert('New guy is destroyed', $orm->people($fetch_new_guy->id) === NULL);

            $data = new stdClass();
            $data->name = 'New Guy';
            $data->email = 'new@guy.com';

            $new_person = $orm->people->_new($data);
            $test->assert('New guy is created using an object', $new_person instanceof ExoBase_Record);
            $test->assert('New guy can be saved', $new_person instanceof ExoBase_Record && $new_person->_save());
            $test->assert('New guy has an id', $new_person instanceof ExoBase_Record && $new_person->id !== NULL);
            $fetch_new_guy = $orm->people($new_person->id);
            $test->assert('New guy can be retrieved', $fetch_new_guy instanceof ExoBase_Record && $fetch_new_guy->id == $new_person->id && $fetch_new_guy->name == $new_person->name && $fetch_new_guy->email == $new_person->email);
            $test->assert('New guy can be destroyed', $new_person->_destroy());
            $test->assert('New guy is destroyed', $orm->people($fetch_new_guy->id) === NULL);

            $new_employee = $orm->employees->_new(array('name' => 'New Guy', 'email' => 'new@guy.com', 'department' => 'New Department'));
            $test->assert('New guy is created as an employee using an array', $new_employee instanceof ExoBase_Record);
            $test->assert('New guy can be saved', $new_employee instanceof ExoBase_Record && $new_employee->_save());
            $test->assert('New guy has an id', $new_employee instanceof ExoBase_Record && $new_employee->id !== NULL);
            $fetch_new_guy = $orm->employees($new_employee->id);
            $test->assert('New guy can be retrieved', $fetch_new_guy instanceof ExoBase_Record && $fetch_new_guy->id == $new_employee->id && $fetch_new_guy->name == $new_employee->name && $fetch_new_guy->email == $new_employee->email && $fetch_new_guy->department == $new_employee->department);
            $test->assert('New guy can be destroyed', $new_employee->_destroy());
            $test->assert('New guy is destroyed', $orm->employees($fetch_new_guy->id) === NULL);
            $before_id = $fetch_new_guy->id;
            $test->assert('The non-deleted employee record (with the same data as that destroyed) can be saved, resurrecting him', $fetch_new_guy->_save());
            $test->assert('The ID of this guy should be the same as before', $fetch_new_guy->id == $before_id);
            $fetch_new_guy = $orm->employees($fetch_new_guy->id);
            $test->assert('New guy can be retrieved AGAIN after resurrection', $fetch_new_guy instanceof ExoBase_Record);
            $test->assert('And he can be deleted again with OBLITERATE mode', $fetch_new_guy->_destroy(TRUE));
            $test->assert('And stay deleted now', $orm->employees($before_id) === NULL);
            $test->assert('Verify deleted using name', count($orm->employees(array('where' => array(array('name', 'starts_with', 'New'))))) == 0);

            // verify that when a record is deleted, all of its relationships are also deleted
            // to do this, create a person, friend steve, make a copy of that person, delete the original person, resurrect them (save the non-destroyed person) and make sure they have no friends.. if they do, it failed
            $friendly_guy = $orm->people->_new(array('name' => 'Friendly', 'email' => 'friendly@guy.com'));
            $test->assert('Create a friendly guy', $friendly_guy->_save() && $friendly_guy->id !== NULL);
            $steve = $orm->people(3);
            $test->assert('Add Steve as his friend', $friendly_guy->friends->_link($steve));
            $get_friend = $friendly_guy->friends[0];
            $test->assert('Verify Steve was added as a friend', $get_friend->id == $steve->id && $get_friend->name == $steve->name);
            $friendly_id = $friendly_guy->id;
            $copy_guy = $orm->people($friendly_id);
            $test->assert('Get a copy of the guy', $copy_guy->id == $friendly_guy->id);
            $test->assert('Destroy the original friendly guy using obliterate mode', $friendly_guy->_destroy(TRUE));
            $test->assert('Verify he was destroyed', $orm->people($friendly_id) === NULL);
            $test->assert('Resurrect him by saving the copy', $copy_guy->_save());
            $resurrect_get = $orm->people($friendly_id);
            $test->assert('Verify he is resurrected', $resurrect_get instanceof ExoBase_Record && $resurrect_get->id == $copy_guy->id);
            $test->assert('As he was destroyed, his friends should also have been destroyed', $resurrect_get instanceof ExoBase_Record && count($resurrect_get->friends) == 0);
            $test->assert('Destroy him again', $resurrect_get instanceof ExoBase_Record && $resurrect_get->_destroy());
            $test->assert('Stay destroyed', $orm->people($friendly_id) === NULL);

            // if a record does not have an id, it cannot be destroyed
            $bad_person = $orm->people->_new();
            $success = FALSE; try { $bad_person->_destroy(); } catch (ExoBase_Exception $e) { $success = TRUE; }
            $test->assert('A new entity without an ID cannot be destroyed, throwing an exception', $success);
            $test->assert('That new person can be saved', $bad_person->_save());
            $test->assert('That new person (now that they have an ID, can be destroyed)', $bad_person->_destroy());
            $success = FALSE; try { $bad_person->_destroy(); } catch (ExoBase_Exception $e) { $success = TRUE; }
            $test->assert('And since they don\'t have an ID again, can\'t be destroyed', $success);

            // verify a container can be deleted
            $bob1 = $orm->people->_new(array('name' => 'Bob 1'));
            $test->assert('Bob 1 can be saved', $bob1->_save());
            $bob2 = $orm->people->_new(array('name' => 'Bob 2'));
            $test->assert('Bob 2 can be saved', $bob2->_save());
            $bobs = $orm->people(array('where' => array('name', 'starts_with', 'Bob')));
            $test->assert('Bobs can be grabbed (there are two)', $bobs instanceof ExoBase_Container && count($bobs) == 2);
            $test->assert('Bobs can be detroyed as a container', $bobs instanceof ExoBase_Container && $bobs->_destroy());
            $test->assert('Bobs container should have zero now', $bobs instanceof ExoBase_Container && count($bobs) == 0);
            $bobs = $orm->people(array('where' => array('name', 'starts_with', 'Bob')));
            $test->assert('Bobs refetched are still zero', $bobs instanceof ExoBase_Container && count($bobs) == 0);

            // verify a unique attribute returns a record and not a container
            $gui = $orm->people(array('where' => array(array('email', '=', 'gui@exodusmedia.ca'))));
            $test->assert('A unique attribute (email) returns a single record, not a container', $gui instanceof ExoBase_record && $gui->id == 1);

            /*
            // sub-container
            $people = $base->people;
            $gui = $people(array('where' => 1));
            $test->assert('Get Gui by using $people(array(\'where\' => 1)) sub-container method', $gui->name == 'Gui');
            
            // sub-container alternate
            $parallel_gui = $people(1);
            $test->assert('Get Gui by using alternate $people(1) sub-container method', $parallel_gui->name == 'Gui');

            // change name to Guillaume should reflect in container
            $gui->name = 'Guillaume';

            $new_gui = $people(1);
            $test->assert('Gui\'s information is changed in a sub-container', $gui->name == 'Guillaume');
            $test->assert('... The parent container is unaffected by the change below it', $new_gui->name == 'Gui');
            $test->assert('... The parallel Gui instance is unaffected by the change', $parallel_gui->name == 'Gui');

            $gui->_save();
            $changed_gui = $people(1);
            $test->assert('Saved sub-container version of Gui should update parent', $changed_gui->name == 'Guillaume');
             */

        $end = microtime(TRUE);

        $diff = abs($end - $start);
        printf("<hr />%0.5f seconds elapsed, %d %s used", $diff, $orm->_container_count, s($orm->_container_count, 'container'));
        ?>
            </body>
        </html>
        <?php
    }
}
