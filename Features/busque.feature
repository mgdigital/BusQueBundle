Feature: Command Bus Queue
  In order to queue and schedule asynchronous commands
  As a developer
  I need a Command Bus Queue

  Scenario: Queuing a command
    Given the queue has been emptied
    Then there should be 0 commands in the queue
    And I queue "test_command"
    Then there should be 1 commands in the queue
    And the command should have a status of "queued"
    When I run the queue worker
    Then the command should have run
    And the command should have a status of "completed"
    And there should be 0 commands in the queue

  Scenario: Queuing commands with identifiers
    Given the queue has been emptied
    And I queue "test_command" with ID "test_command_id"
    And I queue "second_test_command" with ID "test_command_id"
    Then there should be 1 commands in the queue
    And I queue "third_test_command" with ID "another_command_id"
    Then there should be 2 commands in the queue
    And I run the queue worker
    And I run the queue worker
    Then there should be 0 commands in the queue
    And I queue "test_command" with ID "test_command_id"
    Then there should be 1 commands in the queue
    And I run the queue worker
    Then there should be 0 commands in the queue

  Scenario: Queuing a command which fails
    Given I queue "test_command"
    And the command will throw an exception when it is handled
    Then the command should have a status of "queued"
    When I run the queue worker
    Then the command should have a status of "failed"

  Scenario: Scheduling a command
    Given the queue has been emptied
    And I schedule "test_command" to run at 15:00
    And the time is 14:50
    And the command should have a status of "scheduled"
    When I run the scheduler worker
    Then there should be 0 commands in the queue
    Then the time is 15:01
    When I run the scheduler worker
    Then there should be 1 commands in the queue
    And the command should have a status of "queued"
    When I run the queue worker
    Then the command should have run
    And the command should have a status of "completed"
    And there should be 0 commands in the queue
