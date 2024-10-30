=== Campaigns Integrator ===
Contributors: wp_candyman
Donate link: http://databytebank.com/
Tags: zoho, Campaigns, contact form 7, contact, contact form, cf7, form, web form,wp contact form,wp form,wordpress form,wordpress contact form, feedback, feedback form, short code
Requires at least: 3.2.1
Tested up to: 4.3.3
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple wordpress plugin to integrate Wordpress with Zoho Campaigns. This plugin will help you to insert a pre-built form in any page or post.

== Description ==

A simple wordpress plugin to integrate Wordpress with Zoho Campaigns. This plugin will help you to insert a form in any page or post by inserting a short code '[zohocampaign list_key=""]' where list_key value is the List Key for the mailing list which can be found in 'List Tools' tab at https://campaigns.zoho.com/home.do#contacts/list. Plugin supports the following fields in the form First Name and Email. Both are mandatory fields as they are required to create the contact in Zoho Campaigns.

You can use the various attributes in the short code to enable the different features of the plugin.
For example to enable the recaptcha you can write the short code as  [zohocampaign recaptcha="enable"].

The form data will be used to insert a contact into the Zoho Campaigns. The plugin supports recaptcha in the form to stop spam. The settings page can be accessed on the left side bar. For support <a href="http://databytebank.com/" target="_blank">visit plugin site</a>.

> <strong>Supports Contact Form 7</strong><br>
This plugin can now be integrated with Contact Form 7 plugin. Use the attribute 'contact_form7_id' to show a form done in Contact Form 7. For example [zohocampaign contact_form7_id="5"] , where 'contact_form7_id' is the id of the form. 

Requirements:-

Plugin requires cURL php extension enabled. 
For integrating with Contact Form 7 you have to install and activate Contact Form 7 plugin.


== Installation ==

1. Upload `zoho-campaigns-integrator` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the settings page of the plugin by clicking the 'Zoho Campaigns Integrator' link under the wordpress settings menu in the left side bar.
4. Enter the Auth Token for your Zoho Campaigns account into the text filed provided.
5. You can generate the Auth Token after logging into your Zoho Campaigns account and visiting the link https://campaigns.zoho.com/home.do#settings/api
6. You can insert the pre-built lead form into any post or page by inserting the short code '[zohocampaign]'

== Frequently Asked Questions ==


= What is Auth Token ? =

The Auth Token is user-specific and is a permanent token. It is required to make API calls to the Zoho Campaigns.


