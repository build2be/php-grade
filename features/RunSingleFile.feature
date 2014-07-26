Feature: Generate reports
  In order to generate reports I need run the commands with different switches.

  Scenario: Run without arguments
    When I run "bin/php-grade"
    Then STDOUT should contain:
      """
      PHP Grade
      """
    And STDOUT should contain:
    """
    [options] command [arguments]
    """

  Scenario: Run test on directory without options.
    When I run "bin/php-grade run " with "tests/fixtures"
    Then STDOUT should not contain:
      """
      Scanning for files:
      """
    And STDOUT should not contain:
      """
      Running
      """

