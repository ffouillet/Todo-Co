# Authentication  

How to deal with authentication with **Symfony 4.1.x**.

This document is intended for junior developers who want to join the project.  
If you are a senior Symfony developer, you can use things described here as a reminder or you can contribute to this document.

## A word about Symfony 4.x Security 

Unlike older versions (3.x and older), Symfony 4.1 doesn't come with the security component by default.  
If you wish to use it, you'll have to get it.  
With composer, just run following command :  
```
composer require symfony/security
```

## How does it works ?  

### Authentication

In Symfony, authentication is the mechanism that tells the application firewall "who you are" as an user.  
The **firewall** is the component that handle authentication in Symfony (and authorizations which will be covered below).
In the eyes of the application, you can be either Anonymous or Authenticated.
* **Anonymous** : The default status you have as an user if you come into the application without logging in.
* **Authenticated** : You get this status after logging in (via a login form or with a 'remember me' cookie)

With these two status, you can securize your application by for example restricting access to a part of your application only for authenticated users.  
Anonymous users could be redirected (for example) to the login page.

### Authorization

Okay, you're now Authenticated into the application, great. You want to access an application area restricted to administrators but you can't because you must be an administrator and you're actually a simple user so the application throws you a 403 Forbidden.  
This is where Autorization (and Roles) comes into place.  

**Authorization** allows you to restrict access to your Authenticated Users by giving them 'roles'.  
**Roles** are simple arbitrary strings such as 'ROLE_USER' or 'ROLE_ADMIN' or 'ROLE_WHATYOUWANT'.
Security and Roles configuration are available in the ```config/packages/security.yaml```  file. You can configure roles available for your Authenticated Users in the **roles_hierarchy** configuration part as well as restricted access areas in the **access_control** part (in example : areas that would require 'ROLE_ADMIN') .

### Authentication & Authorization configuration in our project

#### Security Configuration file

This part consider you are familiar with yaml format. 
If not, please check what is the [yaml format](https://symfony.com/doc/4.1/components/yaml/yaml_format.html).
Configuration for authentication and authorization can be found in the ```config/packages/security.yaml``` file that we will describe here.

* ```security``` : Refers to the Symfony security component, the entry point of security configuration. Don't remove it !  

    * ```encoders``` : Encoding type that you will use for your Users passwords. In this project, we use ```bcrypt```, but there are others you can use. See [Symfony Security Configuration Reference](https://symfony.com/doc/4.1/reference/configuration/security.html) to know about others encoders available.  
    
    * ```providers```: Providers or 'User providers' allow you to define from where you want to get your users. The firewall use it to get users and identify them. In our case, we load users from the database with our entity ```App\Entity\User``` class and its ```username``` property. 
    
    * ```firewalls```: This defines firewalls for your application (an application can have multiple firewalls). A firewall is an **Authentication** system. You can see that our project have a ```dev``` firewall and a ```main``` firewall.  
        * The ```dev``` firewall is a fake firewall living here just in order to avoid that we don't accidentally block Symfony's dev tools which lives under URLs like ```/_profiler``` and ```/_wdt```.  
        * The ```main``` firewall is the 'main' and only real firewall for the application. We can see the ```anonymous``` key which indicates that this firewall is accessible by Anonymous Users. The ```guard``` key is the part telling that this project use Guard Authentication in order to authenticate users. Don't worry, this we'll be covered below. We could for example remove the ```guard``` key and set ```http_basic: ~``` instead in order to use the old-school prompt to authenticate users. We could have set the ```pattern``` key to : ```^/``` for more clarity but not setting it make the firewall behave the same as if the pattern key was declared as mentionned (protect the whole application). 
        * The ```logout``` key simply indicates the path for application logout.
        
    * ```access_control```: This is the **Authorization** system. It allows you to define routes or patterns of routes that will be restricted to Authenticated users that have specific roles.
    We can see for example here that the path ```^/``` which means that all urls starting with / (the whole application) is restricted to Authenticated users having the role ```ROLE_USER```.  
    In this project every users have this role by default (see getRoles() method in the App\Entity\User Class).
    
    * ```role_hierarchy``` : This key lets you define Roles for your project. Role are arbitrary strings and must start with ```ROLE_```. 
    In our project, we defined two roles : ```ROLE_ADMIN``` and ```ROLE_USER```. You can also see the line ```ROLE_ADMIN: [ROLE_ADMIN,ROLE_USER]``` which means that ```ROLE_ADMIN``` includes ```ROLE_USER```. 
    In other words, an user having ```ROLE_ADMIN``` also have ```ROLE_USER```. If you want to add another roles to this project (i.e. ```ROLE_TASK_MODERATOR```), that's here you have to operate.

#### Authenticating users in the project

Users can authenticate into the application with a login form (URL : ```/login```).

In order to authenticate users in our application, it takes following elements :
* an [User Class](https://github.com/ffouillet/Todo-Co/blob/master/src/Entity/User.php)
* a [Login Route and Controller that will handle Login Action according to Login Route](https://github.com/ffouillet/Todo-Co/blob/master/src/Controller/SecurityController.php). Route is defined with ```@Route annotation``` just above login action in [SecurityController](https://github.com/ffouillet/Todo-Co/blob/master/src/Controller/SecurityController.php).
* a [Login Template](https://github.com/ffouillet/Todo-Co/blob/master/templates/security/login.html.twig)
* an [Authentication Mechanism](https://github.com/ffouillet/Todo-Co/blob/master/src/Security/LoginFormAuthenticator.php) named Guard Authenticator

A word about Guard Authenticator :  
This project use the [Guard Authenticator](https://symfony.com/doc/current/security/guard_authentication.html) mechanism to authenticate users.
Guard Authentication is an easy to set up authentication system, it also allows us to have complete control over our login form. That's why this project use it. Guard Authenticators allows you to totally custom the user authentication process, you could also use Guard Authenticators to log users in with an API Key for example.
In the previous step (```config/packages/security.yaml```), you've seen that the ```main``` firewall have a ```guard``` key and this key had an ```authenticator``` key which points to the corresponding [Guard Authenticator Class](https://github.com/ffouillet/Todo-Co/blob/master/src/Security/LoginFormAuthenticator.php).
If you are unfamiliar with Guard Authenticator and want to learn exactly how it works, please looks both following Symfony Official documentation pages which are well explained and easy to understand :  
* [Setting up a login form with Guard Authenticator](https://symfony.com/doc/current/security/form_login_setup.html)
* [Custom Authenticator System with Guard](https://symfony.com/doc/current/security/guard_authentication.html). The [methods](https://symfony.com/doc/current/security/guard_authentication.html#the-guard-authenticator-methods) part is really interessant to understand how this authentication mechanism works.

#### Restricting access to users in the project

##### With Access Control

The most easy way to secure a part of the application is dealing with the ```access_control``` key in the ```config/packages/security.yaml``` file.  
Here is our access control definition : 
 
```
    access_control:
        - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/, roles: ROLE_USER }
```
We can see that:
* the ``` /login ``` path is accessible with users with role ```IS_AUTHENTICATED_ANONYMOUSLY``` which means thats Anonymous Users can reach this route.   
**Warning**: In order to avoid common pitfalls, beware of how you protect this route. For example, changing the role requirement of this route to ```ROLE_USER ``` would make the login form unreachable for Anonymous users and possibily break your application.

* the whole application (```path: ^/ ```) is restricted to users with ```ROLE_USER``` (every logged-in users).

Login path is accessible for Anonymous Users because it has been declared before the ```{ path: ^/, roles: ROLE_USER }``` and therefore have precedence on it. (You can try to swap rules in and try to reach login path in order to see what happens if you are curious).

##### With Security Annotation in Controllers

You can secure a Controller action with the ``` @Security``` annotation above controller methods definition. Securing that way is a [recommended practice](https://symfony.com/doc/3.4/best_practices/security.html#authorization-i-e-denying-access).  
For example :  
``` 
    class TaskController extends Controller
    {
        /**
        * @Security("has_role('ROLE_ADMIN')")
        */
        public function deleteTask() {
            // Method logic
        }
    }
```
With that, the deleteTask action would be restricted to users having ```ROLE_ADMIN```. 

You can also restrict access to the whole Controller with :  
For example :  
``` 
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    class TaskController extends Controller
    {
        // TaskController methods
    }
```

The import rule is not visible in previous examples but don't forget to set it ! 


##### With Voters 

You can also use [Voters](https://symfony.com/doc/current/security/voters.html) if you need to use a more granular way to restrict access to pages or ressources (i.e. denying specific user access to a specific ressource).  
This project users Voters :
* [TaskVoter](https://github.com/ffouillet/Todo-Co/blob/master/src/Security/TaskVoter.php)
* [UserVoter](https://github.com/ffouillet/Todo-Co/blob/master/src/Security/UserVoter.php)
It allows us to control who can delete a task for example :
In the TaskController's deleteTask action we call Voters mechanism with denyAccessUnlessGranted method : 
```
 /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTask(Task $task)
    {
        // Only task author can delete his task. Tasks without an author can be deleted only by an author with ROLE_ADMIN
        $this->denyAccessUnlessGranted('delete', $task, "Désolé, seul les auteurs de leurs tâches peuvent les supprimer.");
        
        [...]
    }

```
The ```denyAccessUnlessGranted``` method takes here arguments : 
* ```attributes``` :  here is 'delete', which will be used by Voters along with the ```subject``` to determine if it has to handle the current case (here is deleting a task).
* ```subject``` (optionnal): is the $task object here. As its name, it's the element the Voters will deal with to allow or deny access for the current case (here is deleting a task).
* ```message``` (optionnal): message for the AccessDeniedException that will be thrown if access is denied.

Once the ```denyAccessUnlessGranted``` is called all voters checks with ```attributes``` and ```subject``` if they have to handle the current case (deniying or allowing access).

Here is how it works inside Voters when ```isGranted()``` or ```denyAccessUnlessGranted()``` methods are called:
1.  ```Voter::supports($attribute, $subject)``` method is called. 
It determines if the current Voter has to handle the current case with the $attribute and $subject parameters. 
If this methods return false, the current Voter don't have to handle the current case and its jobs stops here.
If the methods return true, the current Voter is concerned and the next Voter method called is ```voteOnAttribute($attribute, $subject, TokenInterface $token)```.

2. ```Voter::voteOnAttribute($attribute, $subject, TokenInterface $token)``` method is called.  
The goal of this method is to return true to grant access or false to deny access (and throw an AccessDeniedException) along with ```$attribute``` and ```$subject``` arguments.
The ```$token``` attribute allows you for example to get the current logged in user.
So, if this voter method returns ```true```, the rest of the controller's action is executed (in our case, delete the task) and if it returns false, it denies access and don't execute the rest of the controller's action by throwing an AccessDeniedException with message equal to ```denyAccessUnlessGranted```'s ```$message``` attribute if specified (default message : ```Access denied```).

This was a short introduction to Voters, if you want to go deeper with this notion, please check [Symfony's Voters official documentation](https://symfony.com/doc/current/security/voters.html).

##### In Twig Templates

You can even use Authorization with Roles or Voters in twig templates (with is_granted() function).  
If you want for exemple to hide or show a specific part of template with:  
```twig
    {# Call Voters #}
    {% if is_granted('edit', task) %}
        <!-- edit button  -->
    {% endif %}
    
    {# With Roles directly #}
    {% if is_granted('ROLE_ADMIN') %}
        <!-- delete button -->
    {% endif %}
```

### Conclusion
With these informations, you are now able to manage and modify this application security and others in the future.
You can for exemple add roles and requirements to controllers or actions, show specific informations in templates etc.
If you find something hard to understand or want to go deeper with some notions, don't forget to check Symfony's official documentation.
If somethings seems to you wrongly explained in this document, you can fork the project and create Pull Requests to submit modifications or simply send me a message.

Thanks for reading.



