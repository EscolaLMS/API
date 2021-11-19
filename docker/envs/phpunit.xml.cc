<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" backupGlobals="false" backupStaticAttributes="false" bootstrap="vendor/autoload.php" colors="true" convertErrorsToExceptions="true" convertNoticesToExceptions="true" convertWarningsToExceptions="true" processIsolation="false" stopOnFailure="false" xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.3/phpunit.xsd">
  <coverage processUncoveredFiles="true">
    <include>
      <directory suffix=".php">./vendor/escolalms/auth/</directory>
      <directory suffix=".php">./vendor/escolalms/cart/</directory>
      <directory suffix=".php">./vendor/escolalms/categories/</directory>
      <directory suffix=".php">./vendor/escolalms/core/</directory>
      <directory suffix=".php">./vendor/escolalms/courses/</directory>
      <directory suffix=".php">./vendor/escolalms/courses-import-export/</directory>
      <directory suffix=".php">./vendor/escolalms/files/</directory>
      <directory suffix=".php">./vendor/escolalms/headless-h5p/</directory>
      <directory suffix=".php">./vendor/escolalms/images/</directory>
      <directory suffix=".php">./vendor/escolalms/pages/</directory>
      <directory suffix=".php">./vendor/escolalms/payments/</directory>
      <directory suffix=".php">./vendor/escolalms/reports/</directory>
      <directory suffix=".php">./vendor/escolalms/scorm/</directory>
      <directory suffix=".php">./vendor/escolalms/settings/</directory>
      <directory suffix=".php">./vendor/escolalms/tags/</directory>
      <directory suffix=".php">./vendor/escolalms/topic-types/</directory>
      <!-- <directory suffix=".php">./vendor/escolalms/video/</directory> -->

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
    <testsuite name="files">
      <directory suffix="Test.php">./vendor/escolalms/files/tests</directory>
    </testsuite>
    <testsuite name="headless-h5p">
      <directory suffix="Test.php">./vendor/escolalms/headless-h5p/tests</directory>
    </testsuite>
    <testsuite name="images">
      <directory suffix="Test.php">./vendor/escolalms/images/tests</directory>
    </testsuite>
    <testsuite name="payments">
      <directory suffix="Test.php">./vendor/escolalms/pages/tests</directory>
    </testsuite>
    <testsuite name="payments">
      <directory suffix="Test.php">./vendor/escolalms/payments/tests</directory>
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
    <testsuite name="tags">
      <directory suffix="Test.php">./vendor/escolalms/tags/tests</directory>
    </testsuite>
    <testsuite name="topic-types">
      <directory suffix="Test.php">./vendor/escolalms/topic-types</directory>
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
  </php>
</phpunit>
