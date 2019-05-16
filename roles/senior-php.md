## Senior PHP Developer Challenge

### Introduction
Your local electronics store has started to expand, but track their entire inventory by hand.  They have asked you to build a simple cataloging system as a REST API so that they can integrate with mobile and desktop applications in the future.

You are free to use any PHP libraries or modules in order to complete the challenge.  You may choose either MySQL/MariaDB or MongoDB as your data layer.

**The challenge is to be completed using Symfony4.**

#### Bonus Points
* Use Docker to build your solution
* Use preferably PHPUnit, Codeception or Behat for your unit tests

### Requirements

The API should be able to:
* list all products
* list all categories
* retrieve a single product
* create a product
* update a product
* delete a product

#### Authentication
Only authenticated users can create, update, or delete a product.  No authentication is required to retrieve or list.

#### Data
> All entities should have timestamp fields (created_at, and modified_at)

Products have the following attributes: 
* name
* category
* SKU
* price
* quantity

Categories have the following attributes:
* name

##### Seed Data
Import the contents of [electronic-catalog.json](../data/seeds/electronic-catalog.json) into your database of choice.  It's up to you how you want to construct relations.

### Criteria
For full transparency, the test will be scored according to the following:
* REST Structure
* Unit Testing
* Logging
* Use of services, controllers, and models
* Use of Symfony4 as a framework
* Best practices
* Reusable code
* Decoupled code
* Ability to transform requirements into code
