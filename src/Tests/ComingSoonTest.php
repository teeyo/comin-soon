<?php

namespace Drupal\coming_soon\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Tests the Drupal 8 coming_soon module functionality
 *
 * @group coming_soon
 */
class ComingSoonTest extends WebTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = array('coming_soon', 'node');

  /**
   * A simple user with 'access content' permission
   */
  private $user;

  /**
   * Perform any initial set up tasks that run before every test method
   */
  public function setUp() {
    parent::setUp();
    $this->user = $this->drupalCreateUser(array('administer coming soon'));
  }

  /**
   * Tests that the 'admin/config/coming_soon' path returns the right content
   */
  public function testAdminPageAccessible() {
    // Login
    $this->drupalLogin($this->user);
    // Test the page is found
    $this->drupalGet('admin/config/coming_soon');
    $this->assertResponse(200);
  }

  /**
   * Tests that a user without the right permission can't manage coming soon module
   */
  public function testPermissionOnAdminPage() {
    $this->user = $this->drupalCreateUser(array('access content'));
    // Login
    $this->drupalLogin($this->user);
    // Test the page is found
    $this->drupalGet('admin/config/coming_soon');
    $this->assertResponse(403);
  }

}