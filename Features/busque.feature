Feature: CommandBus Queue
  In order to queue asynchronous commands
  As a developer
  I need a CommandBus Queue

  Scenario: Queuing a command
    Given the queue has been emptied
    Then there should be 0 commands in the queue
    And I queue a command
    Then there should be 1 commands in the queue
    And the command should have a status of "queued"
    When I run the queue worker
    Then the command should have run
    And the command should have a status of "completed"
    And there should be 0 commands in the queue

  Scenario: Scheduling a command
    Given the queue has been emptied
    And I schedule a command to run at 15:00
    And the time is 14:50
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
