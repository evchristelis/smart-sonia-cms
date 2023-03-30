=== DIOT SCADA with MQTT ===
Contributors: scada
Donate link: https://www.integraxor.com/?utm_source=wp&utm_content=donate
Tags: SCADA, MQTT, IoT, IIoT, Industrial 4.0, Automation, Control, Monitoring
Requires at least: 4.7.3
Tested up to: 4.8
Stable tag: 1.0.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

DIOT SCADA is for Home Automation/Industrial 4.0 realtime monitoring. It subscribes to MQTT sensors /IoT devices to display live data with shortcode.

== Description ==


DIOT which stands for Decoupled IOT, has its SCADA functionalities decoupled into Host and Node for flexibility and scalability that catered for IoT era. This plugin functions as the SCADA Host to work with your device or system, which will be treated as SCADA Node.


The supported IoT protocol is MQTT. You just need to enter the MQTT broker/server into the configuration. You may then subscribe to the desired topic with a shortcode to display in any desired web page or post.
```
[diot topic="building/floor/device/sensor"]
```
Or, if you have a JSON content, you may add dollar sign as JSON root:
```
[diot topic="building/floor/device/sensor$json.data"]
```
The content will be updated dynamically when the device publish any data.




You may also choose to display your realtime data in trending chart. Check out [Ecava DIOT online demo](https://www.integraxor.com/diot-demo/?utm_source=wp) to see how easy things can be done now!




== Installation ==

Installing SCADA Host is simple:


1. Go to your wordpress plugin section, then select 'Add new' and search for 'DIOT SCADA'.
1. Install and activate this DIOT SCADA plugin.
1. Enter the MQTT broker/server URL.
1. Use shortcode in your page.
                ```
        `[diot topic="building/floor/device/sensor"]`
                ```
1. Feed in runtime data with Ecava IGX SCADA or any MQTT sensor.


== Frequently Asked Questions ==

= What's SCADA Node? =

SCADA Node can be your own MQTT sensor, or a stand alone [ECAVA IGX SCADA](https://www.integraxor.com/?utm_source=wp) that feeding data into this plugin Host.

= Why I can't enter credential in the default MQTT broker? =

The default MQTT broker is provided free and open for public access. You should rent a server to host your own private broker, or [subscribe to MQTT broker](https://www.integraxor.com/buy-scada/?utm_source=wp) for sensitive data or production purpose.

= How do I animate graphic according to live data? =

You may [upgrade to professional edition](https://www.integraxor.com/buy-scada/?utm_source=wp) to use private MQTT broker, as well as SVG mimic with Inskcape SAGE - SCADA Animation Graphic Editor.

= What is SCADA? =

SCADA stands for Supervisory Control And Data Acquisition, which is commonly used for industrial control and monitoring. [Read more… ](https://www.integraxor.com/what-is-scada/?utm_source=wp)

= Where to learn more? =
[Plugin Website](https://www.ecava.com/decoupled-iot-scada/?utm_source=wp&utm_content=faq)


== Screenshots ==


1. Realtime trending chart sample.


== Changelog ==

= 1.0.5.1 =
* Fixed settings page port configuration issue from 1.0.5 update

= 1.0.5 =
* Added format parameter to format numbers

= 1.0.4 =
* Fixed chart not displayed when positioned after text mqtt data display.

= 1.0.3 =
* Added description on how to use trend chart feature.

= 1.0.2 =
* Added basic trend chart feature.

= 1.0.1 =
* Changed method of selecting json value by using jsonPath within the topic parameter.
* Added support for data-types: int, uint, boolean, real32, real64.

= 1.0.0 =
* Initial release on 2017.05.08

== Upgrade Notice ==

= 1.0.5.1 =
Fixes settings page port configuration issue from 1.0.5 update.

= 1.0.5 =
Adds number formatter function to the MQTT messages retrieved.

= 1.0.4 =
bug fix for trend chart feature.

= 1.0.3 =
Adds a way to display data published in MQTT in a trend chart.

= 1.0.2 =
Adds a way to display data retrieved from MQTT in a trend chart.

= 1.0.1 =
Adds flexibility in json selection and data type handling for non string type payload data.

= 1.0.0 =
Thank you for using Ecava DIOT SCADA.