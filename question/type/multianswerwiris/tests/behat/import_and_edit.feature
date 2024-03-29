@qtype @qtype_multianswerwiris @qtype_multianswerwiris_edit
Feature: Test importing and editing multianswerwiris questions
  As a teacher
  In order to reuse and editing multianswerwiris questions
  I need to import them and then edit them

  Background:
    Given the "wiris" filter is "on"
    Given the "mathjaxloader" filter is "disabled"
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | T1        | Teacher1 | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage

  @javascript @_file_upload
  Scenario: Import and edit multianswerwiris question.
    When I navigate to "Question bank > Import" in current page administration
    And I set the field "id_format_xml" to "1"
    And I upload "question/type/multianswerwiris/tests/fixtures/testquestion.moodle.xml" file to "Import" filemanager
    And I press "id_submitbutton"
    Then I should see "Parsing questions from import file."
    And I should see "Importing 1 questions from file"
    And I should see "1."
    And I press "Continue"
    Then I choose "Edit question" action for "Cloze Wiris Test Question" in the question bank
    And I set the following fields to these values:
      | Question name | Edited Cloze Wiris Test Question                                                              |
      | Question text | <p>Type -10: {:SA:=\#a}</p> <p>Type 5: {:SA:=5}</p> <p>Type 5: {:SA:=5}</p> <p>Formula #b</p> |
    And I press "id_submitbutton"
    And I should see "Edited Cloze Wiris Test Question"
