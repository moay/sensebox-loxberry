# sensebox-loxberry
A plugin for Loxberry which will allow to use data from a senseBox for home automation with Loxone.

Work in progress.

## About the plugin

This plugin pulls data from senseBox boxes by use of the opensensemap api. If you want to connect your own
senseBox to this plugin, you will need to make it push to the opensensemap first and then be able
to pull the values from there.

### Prefinal version

The plugin was built using LoxBerry Poppins, which is not finally released yet. Therefore, the plugin is not in a final state. This will change soon. 

## Setup instructions and Documentation

Wiki page coming soon.

Basically just enter LoxBerry plugin management and install the plugin from this url: 
```
https://github.com/moay/sensebox-loxberry/archive/0.9.0-alpha.zip
```

Or just download the zip file and upload it to your LoxBerry.

### Direct pulling from the senseBox without using the API

Direct pulling from the senseBox is not planned for two reasons:

1. this would contradict the idea of having senseBoxes all around the globe that will help to create
some sort of open data collective.
2. this would make it harder to setup the senseBox as setup and usage would differ from the official
docs.

## About senseBox

senseBox, as well as all related visual or technical files and data, is published under the CC-BY-SA license
for general public use.

Read more about the project on [sensebox.de](https://sensebox.de/).

## License

The plugin is licensed under the MIT License.

---

[![Build with LoxBerry Poppins](https://user-images.githubusercontent.com/3605512/73123470-9c661e00-3f90-11ea-91d6-6f150b6d828a.png)](https://github.com/loxberry-poppins-base-plugin)
