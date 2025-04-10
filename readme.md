DDD REST API
=================
## About

**Aspects I wanted to test in this project**:
- Domain-driven design
- Smoke tests in Nette with the help of Nette Tester
- Minimal amount of external dependencies besides Nette itself

**What I started but didn't finish**:
- *Proper Access Token management*. The current version has a date of expiration, but it's not being used.  
  It would be easier to refactor the current code to have a single accessToken as an attribute of the User entity.
- *Proper hierarchy of exceptions* and error handling
- *Refactoring of Tests*, especially PresenterTestHelper isn't nice, and ArticleApiEndpointTest.php  
  would benefit from some code reuse and/or DataProviders.
- `PUT /users/{id}` and `DELETE /users/{id}` endpoints are not implemented. I hope the rest of the project 
suggest how they would be implemented. I will add them maybe later:)

**What I would definitely like to do differently**, but I didn't find a way to do it in Nette out of the box:
- *Single-action Presenters*. Currently, e.g., ArticlePresenter handles 5 different actions; it would be more  
  readable to have a separate Presenter for each action. But you can't route the same URL for different methods  
  to different Presenters. At least not in the default Router.  
  There's a project https://contributte.org/packages/contributte/apitte/ which adds this functionality,  
  but I didn't want to use such a heavy dependency.

Also, for the documentation, I would define the OpenApi schema directly on the Presenters.


## How to run this project

### Via docker

The easiest way to test this app is via docker, just do

`docker-compose up -d`

And everything should be ready soon. 
On `http://localhost:8000/` you can see a welcome message.

During the docker-compose up process, the database will be created and populated with a test 
admin user

E-mail: `admin@example.com` \
Password:  `Admin` \
Access token:  `admin-token`

Examples of API call are in the `http-requests` folder.  

Once the Docker container is running, you can run PHP tests, PHPStan checks, and code style checks using:

`docker exec -it simple-rest-api composer qa`

### Locally

You will need PHP 8.2 with SQLite support and Composer installed.

Install dependencies (this will also create the database):

`composer install`

Then run the built-in PHP server:

`php -S localhost:8000 -t www`

## API Usage

After the setup (either via docker, or locally), you can test the API using curl:
``
curl -H "Authorization: Bearer admin-token" http://localhost:8000/users
``

You should see a response like this:    

```json
[
    {
        "id": 1,
        "name": "Admin",
        "email": "admin@example.com",
        "role": "Admin"
    }
]
```
