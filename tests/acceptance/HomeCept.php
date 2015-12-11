<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('see the Home page');
$I->amOnPage('/');
$I->see('My albums');
$I->seeInDatabase('album', array('artist' => 'Adele', 'title' => '21'));
$I->seeLink('Add new album');
$I->click('Add new album');
$I->see('Title');
$I->see('Artist');
$I->seeCurrentUrlEquals('/album/add');
$I->fillField('title','foo');
$I->fillField('artist','bar');

// enable repopulate and cleanup in codeception.yml to reset the db before each test
// enabld module Db in acceptance.suite.yml to use this feature

// click submit to add the new album
//$I->dontSee('foo');
//$I->click('submit');
//$I->see('foo');
