@qtype @qtype_wq @qtype_truefalsewiris @qtype_truefalsewiris_edit
Feature: Test editing a True/False Wiris question
  As a teacher
  In order to be able to update my True/False question
  I need to edit them
  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | T1        | Teacher1 | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And the following "question categories" exist:
      | contextlevel | reference | name           |
      | Course       | C1        | Test questions |
    And the following "questions" exist:
      | questioncategory | qtype          | name                 | template         |
      | Test questions   | truefalsewiris | true-false-wiris-001 | sciencetruefalse |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I navigate to "Question bank" in current page administration
  Scenario: Edit a True/False question
    When I choose "Edit question" action for "true-false-wiris-001" in the question bank
    And I set the following fields to these values:
      | Question name | |
    And I press "id_submitbutton"
    Then I should see "You must supply a value here."
    When I set the following fields to these values:
      | Question name | Edited true-false-wiris-001 name |
    And I press "id_submitbutton"
    Then I should see "Edited true-false-wiris-001 name"
