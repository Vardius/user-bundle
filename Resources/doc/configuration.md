Vardius - User Bundle
======================================

Configuration
----------------
1. Configure the VardiusUserBundle
2. Overriding a Form Type

### 1. Configure the VardiusUserBundle

If you want to enable username
config.yml

``` yaml
    #app/config/config.yml
    
    vardius_user:
        username: true #default false
        email_from: some@email.com #default hostname
```
        
routing.yml

``` yaml
    #app/config/routing.yml
    
    vardius_user:
        resource: "@VardiusUserBundle/Resources/config/routing.yml"
        prefix:   /
```
        
or enable some routes only:

``` yaml
    #app/config/routing.yml
    
    login_route:
        path:     /login
        defaults: { _controller: VardiusUserBundle:Security:login }
    
    logout_route:
        path:     /logout
        defaults: { _controller: VardiusUserBundle:Security:logout }
    
    login_check:
        path:     /login_check
        defaults: { _controller: VardiusUserBundle:Security:loginCheck }
```
        
security.yml

``` yaml
    #app/config/security.yml
    
    encoders:
        Vardius\Bundle\UserBundle\Entity\UserInterface:
            algorithm: bcrypt
            cost: 12

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: [ ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH ]

    providers:
        vardius:
            id: vardius_user.user_provider

    firewalls:
        admin_area:
            pattern:    ^/
            anonymous: ~
            form_login:
                login_path: login_route
                check_path: login_check
                csrf_provider: form.csrf_provider
            logout:
                path:   logout_route
                target: login_route
                invalidate_session: true
            remember_me:
                key:      "%secret%"
                lifetime: 31536000 # 365 days in seconds
                path:     /
                domain:   ~ # Defaults to the current domain from $_SERVER

    access_control:
        - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/password-reset, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/, roles: ROLE_USER }
```

### 2. Overriding a Form Type
If you want user to put his name when register override user type form

``` php
    // src/Acme/UserBundle/Form/Type/UserType.php
    <?php
    
    namespace Acme\UserBundle\Form\Type;
    
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    
    class UserType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            // add your custom field
            $builder->add('name');
        }
    
        public function getParent()
        {
            return 'vardius_user';
        }
    
        public function getName()
        {
            return 'acme_user';
        }
    }
```

register your form as a service

``` xml
    <service id="acme_user.user.form.type" class="Acme\UserBundle\Form\Type\UserType">
        <tag name="form.type" alias="acme_user" />
    </service>
```

next register your class in config.yml

``` yaml
    #app/config/config.yml
    
    vardius_user:
        user_form: acme_user
```

to override user edit form provide

``` php
    // src/Acme/UserBundle/Form/Type/UserEditType.php
    <?php
    
    namespace Acme\UserBundle\Form\Type;
    
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    
    class UserEditType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            // add your custom field
            $builder->add('name');
        }
    
        public function getParent()
        {
            return 'vardius_edit_user';
        }
    
        public function getName()
        {
            return 'acme_user_edit';
        }
    }
```

register your form as a service

``` xml
    <service id="acme_user.user_edit.form.type" class="Acme\UserBundle\Form\Type\UserEditType">
        <tag name="form.type" alias="acme_user_edit" />
    </service>
```

``` yaml
    #app/config/config.yml
    
    vardius_user:
        user_edit_form: acme_edit_user
```

There is build in terms action,
if you want user to be able read terms, you can for example link form label to it:

``` twig
    <a href="{{ path('account_register_terms') }}">{{ form_label(form.terms, null, {'label_attr': {'class': 'col-md-3 control-label'}}) }}</a>
```

to overrider terms, override its view.

``` twig
    {% extends '@VardiusUser/Registration/terms.html.twig' %}
```

