Feature: SamsonCMS security application

  Background:
    Given I am on homepage
    And I log out
    And I am logged in as "admin@admin.com" with "admin@admin.com"
    And I am on "/security"
    

  Scenario: Group list rendering
    And print last response
    #Given We have filled material table
    Then I should see 1 ".table2.default" element
    
