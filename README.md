be-symfony-33-v2
================

- Commands

    composer update   
    
    php bin/console debug:router // View routers
    
    php vendor/behat/behat/bin/behat --name=login // run behat test scenarios
    
    php bin/console generate:bundle --namespace=OgilvyBundle
    
    composer dump-autoload // reload bundles

A Symfony project created on January 29, 2018, 5:25 am.

service:
        user_provider: fos_user.user_provider.username
        
- using Repository 

    Ex: $repository = $this->getDoctrine()->getRepository(User::class);
        
- Get Current User:

    $user = $this->get('security.token_storage')->getToken()->getUser();
    
- Allow GrantType in Db 

    INSERT INTO `client` VALUES (NULL, '3bcbxd9e24g0gk4swg0kwgcwg4o8k8g4g888kwc44gcc0gwwk4', 'a:0:{}', '4ok2x70rlfokc8g0wws8c8kwcokw80k44sg48goc0ok4w0so0k', 'a:2:{i:0;s:8:"password";i:1;s:13:"refresh_token";}');

- Install Behat
    
    composer require behat/behat --dev
    
    composer require behat/symfony2-extension --dev
    
    composer require guzzlehttp/guzzle --dev
    
    composer require csa/guzzle-bundle --dev
    
    php vendor/behat/behat/bin/behat --init
    
    Modify FeatureContext for drop/create schema 
    
    Add Behat.yml config
    
    Add RestApiContext, UserSetupContext
    
    Add Config_Acceptance
    
    Add app_acceptance.php
    
    Add Enviroment getEnvironment(), ['dev', 'test', 'acceptance'] in App.Kernel
    
    Add $bundles[] = new Csa\Bundle\GuzzleBundle\CsaGuzzleBundle(); in App.Kernel

    
- Modify FOS User Field // Skip
    *** $user = new AppBundle\Entity\User(); //not use $userManager->createUser();
    Add Custom Fields in Entity\User
    
    Add Custum Form\RegistrationType
    
    Modify Config.yml
    
        fos_user:
            db_driver: orm
            firewall_name: api                                  # Seems to be used when registering user/reseting password,
                                                                # but since there is no "login", as so it seems to be useless in
                                                                # our particular context, but still required by "FOSUserBundle"
            user_class: AppBundle\Entity\User
            from_email:
                    address:        support@mywebsite.com
                    sender_name:    mr_mailer
            registration:
                    form:
                        type: AppBundle\Form\RegistrationType
                        name: app_user_registration
                        
    Modify service.yml
        
        app.form.registration:
                class: AppBundle\Form\RegistrationType
                tags:
                    - { name: form.type, alias: app_user_registration }
    
- Override Fos OAuth 

    ref https://www.codevate.com/blog/12-securing-client-side-public-api-access-with-oauth-2-and-symfony
    
- Apply Core Bundle

    Copy Folder CoreBundle
    
    Add CoreBundle to Appkernel
    
    Modify routing.yml
    
    core:
        resource: "@CoreBundle/Controller/"
        type:     annotation
    
    core_ext:
        resource: "@CoreBundle/ControllerExt/"
        type:     annotation

    Modify composer.json 
    
        "CoreBundle\\": "src/CoreBundle"
        
        composer dump-autoload
        
    Modify services.yml
    
        app.website_token_authenticator:
            class: CoreBundle\Security\WebsiteUserTokenAuthenticator
            arguments: ['@doctrine.orm.entity_manager']
        app.admin_token_authenticator:
            class: CoreBundle\Security\AdminUserTokenAuthenticator
            arguments: ['@doctrine.orm.entity_manager']
        kernel.listener.response_listener:
            class: CoreBundle\Listener\ResponseListener
            tags:
               - { name: kernel.event_listener, event: kernel.response, method: onKernelResponse }
                   
    
    Response HTML
    
         format_listener:
                rules:
                    - { priorities: ['json', 'html'], fallback_format: json, prefer_extension: false }