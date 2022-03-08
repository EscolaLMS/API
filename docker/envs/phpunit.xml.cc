<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" backupGlobals="false" backupStaticAttributes="false" bootstrap="vendor/autoload.php" colors="true" convertErrorsToExceptions="true" convertNoticesToExceptions="true" convertWarningsToExceptions="true" processIsolation="false" stopOnFailure="false" xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.3/phpunit.xsd">
  <coverage processUncoveredFiles="true">
    <include>
      <directory suffix=".php">./vendor/escolalms/auth/src</directory>
      <directory suffix=".php">./vendor/escolalms/cart/src</directory>
      <directory suffix=".php">./vendor/escolalms/categories/src</directory>
      <directory suffix=".php">./vendor/escolalms/core/src</directory>
      <directory suffix=".php">./vendor/escolalms/courses/src</directory>
      <directory suffix=".php">./vendor/escolalms/courses-import-export/src</directory>
      <directory suffix=".php">./vendor/escolalms/csv-users/src</directory>
      <directory suffix=".php">./vendor/escolalms/files/src</directory>
      <directory suffix=".php">./vendor/escolalms/headless-h5p/src</directory>
      <directory suffix=".php">./vendor/escolalms/images/src</directory>
      <directory suffix=".php">./vendor/escolalms/lrs/src</directory>
      <directory suffix=".php">./vendor/escolalms/notifications/src</directory>
      <directory suffix=".php">./vendor/escolalms/mailerlite/src</directory>
      <directory suffix=".php">./vendor/escolalms/mattermost/src</directory>
      <directory suffix=".php">./vendor/escolalms/pages/src</directory>
      <directory suffix=".php">./vendor/escolalms/payments/src</directory>
      <directory suffix=".php">./vendor/escolalms/permissions/src</directory>
      <directory suffix=".php">./vendor/escolalms/reports/src</directory>
      <directory suffix=".php">./vendor/escolalms/scorm/src</directory>
      <directory suffix=".php">./vendor/escolalms/settings/src</directory>
      <directory suffix=".php">./vendor/escolalms/stationary-events/src</directory>
      <directory suffix=".php">./vendor/escolalms/tags/src</directory>
      <directory suffix=".php">./vendor/escolalms/topic-types/src</directory>
      <directory suffix=".php">./vendor/escolalms/templates/src</directory>
      <directory suffix=".php">./vendor/escolalms/templates-email/src</directory>
      <directory suffix=".php">./vendor/escolalms/templates-sms/src</directory>
      <directory suffix=".php">./vendor/escolalms/templates-pdf/src</directory>
      <directory suffix=".php">./vendor/escolalms/questionnaire/src</directory>
      <directory suffix=".php">./vendor/escolalms/assign-without-account/src</directory>
      <directory suffix=".php">./vendor/escolalms/tracker/src</directory>
      <!-- <directory suffix=".php">./vendor/escolalms/video/src</directory> -->

    </include>
  </coverage>
  <testsuites>
    <testsuite name="Integrations">
      <directory suffix="Test.php">./tests/Integrations</directory>
    </testsuite>
    <testsuite name="auth">
      <directory suffix="Test.php">./vendor/escolalms/auth/tests</directory>
    </testsuite>
    <testsuite name="cart">
      <directory suffix="Test.php">./vendor/escolalms/cart/tests</directory>
    </testsuite>
    <testsuite name="categories">
      <directory suffix="Test.php">./vendor/escolalms/categories/tests</directory>
    </testsuite>
    <testsuite name="core">
      <directory suffix="Test.php">./vendor/escolalms/core/tests</directory>
    </testsuite>
    <testsuite name="courses">
      <directory suffix="Test.php">./vendor/escolalms/courses/tests</directory>
    </testsuite>
    <testsuite name="courses-import-export">
      <directory suffix="Test.php">./vendor/escolalms/courses-import-export/tests</directory>
    </testsuite>
    <testsuite name="csv-users">
      <directory suffix="Test.php">./vendor/escolalms/csv-users/tests</directory>
    </testsuite>
    <testsuite name="files">
      <directory suffix="Test.php">./vendor/escolalms/files/tests</directory>
    </testsuite>
    <testsuite name="headless-h5p">
      <directory suffix="Test.php">./vendor/escolalms/headless-h5p/tests</directory>
    </testsuite>
    <testsuite name="images">
      <directory suffix="Test.php">./vendor/escolalms/images/tests</directory>
    </testsuite>
    <testsuite name="lrs">
      <directory suffix="Test.php">./vendor/escolalms/lrs/tests</directory>
    </testsuite>
    <testsuite name="notifications">
      <directory suffix="Test.php">./vendor/escolalms/notifications/tests</directory>
    </testsuite>
    <testsuite name="mailerlite">
      <directory suffix="Test.php">./vendor/escolalms/mailerlite/tests</directory>
    </testsuite>
    <testsuite name="mattermost">
      <directory suffix="Test.php">./vendor/escolalms/mattermost/tests</directory>
    </testsuite>
    <testsuite name="payments">
      <directory suffix="Test.php">./vendor/escolalms/pages/tests</directory>
    </testsuite>
    <testsuite name="payments">
      <directory suffix="Test.php">./vendor/escolalms/payments/tests</directory>
    </testsuite>
    <testsuite name="permissions">
      <directory suffix="Test.php">./vendor/escolalms/permissions/tests</directory>
    </testsuite>
    <testsuite name="reports">
      <directory suffix="Test.php">./vendor/escolalms/reports/tests</directory>
    </testsuite>
    <testsuite name="scorm">
      <directory suffix="Test.php">./vendor/escolalms/scorm/tests</directory>
    </testsuite>
     <testsuite name="settings">
      <directory suffix="Test.php">./vendor/escolalms/settings/tests</directory>
    </testsuite>
    <testsuite name="stationary-events">
      <directory suffix="Test.php">./vendor/escolalms/stationary-events/tests</directory>
    </testsuite>
    <testsuite name="tags">
      <directory suffix="Test.php">./vendor/escolalms/tags/tests</directory>
    </testsuite>
    <testsuite name="topic-types">
      <directory suffix="Test.php">./vendor/escolalms/topic-types/tests</directory>
    </testsuite>
    <testsuite name="templates">
      <directory suffix="Test.php">./vendor/escolalms/templates/tests</directory>
    </testsuite>
    <testsuite name="templates-email">
      <directory suffix="Test.php">./vendor/escolalms/templates-email/tests</directory>
    </testsuite>
    <testsuite name="templates-sms">
      <directory suffix="Test.php">./vendor/escolalms/templates-sms</directory>
    </testsuite>
    <testsuite name="templates-pdf">
      <directory suffix="Test.php">./vendor/escolalms/templates-pdf/tests</directory>
    </testsuite>
    <testsuite name="questionnaire">
      <directory suffix="Test.php">./vendor/escolalms/questionnaire/tests</directory>
    </testsuite>
    <testsuite name="assign-without-account">
      <directory suffix="Test.php">./vendor/escolalms/assign-without-account/tests</directory>
    </testsuite>
    <testsuite name="tracker">
      <directory suffix="Test.php">./vendor/escolalms/tracker/tests</directory>
    </testsuite>
    <!--
    <testsuite name="video">
      <directory suffix="Test.php">./vendor/escolalms/video/tests</directory>
    </testsuite>
    -->
  </testsuites>
    <php>
    <env name="APP_KEY" value="AckfSECXIvnK5r28GVIWUAxmbBSjTsmF"/>
    <env name="DB_CONNECTION" value="mysql"/>
    <env name="DB_HOST" value="mysql"/>
    <env name="DB_PORT" value="3306"/>
    <env name="DB_DATABASE" value="test"/>
    <env name="DB_USERNAME" value="root"/>
    <env name="DB_PASSWORD" value="password"/>
    <env name="CONFIG_USE_DATABASE" value="true"/>
  </php>
</phpunit>
