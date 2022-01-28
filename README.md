<div align="center">
  <img src="https://wiki.bitmessage.org/images/f/f1/Bitmessagelogo-reduced.png" alt="Bitmessage.org">

# bitmessage-php
PHP library for api interface in the Bitmessage software.
  
<img src="https://img.shields.io/badge/Bitmessage-Library-green" alt="badge"> 
<img src="https://img.shields.io/badge/Language-PHP-blue" alt="badge">
</div>

### Bitmessage Information
Official Website: https://bitmessage.org <br>
Github Repo: https://github.com/Bitmessage/PyBitmessage <br>
Whitepaper: https://bitmessage.org/bitmessage.pdf <br></br>
Bitmessage is a P2P communications protocol used to send encrypted messages to another person or to many subscribers. It is decentralized and trustless, meaning that you need-not inherently trust any entities like root certificate authorities. It uses strong authentication which means that the sender of a message cannot be spoofed, and it aims to hide "non-content" data, like the sender and receiver of messages, from passive eavesdroppers like those running warrantless wiretapping programs.

### Security
This library cannot protect you from inadvertent exposure of clear-text data to your network that can be read by an adversary.

You need to add SSL like you would on any other webservice, that is the best solution to the issue above.

You can install tcpdump and run it to check if SSL is applied correctly for the webservice if in any doubt.

Find network interface that you want to sniff by typing ``` ip address show ``` in your terminal.
Start tcpdump and sniff POST requests for the selected interface, by typing 
``` tcpdump -i interface -s 0 -A 'tcp[((tcp[12:1] & 0xf0) >> 2):4] = 0x504F5354' ``` in your terminal. 

Reason for doing an approach like above would be to make sure that the entry point and endpoint enforce HTTPS on it's users, because if the entry point would also allow HTTP, then that data would be sniffable even if the endpoint is secure.

### <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/58/Ic_settings_48px.svg/2048px-Ic_settings_48px.svg.png" width="25" height="25" alt="cogs"> Api Reference
https://pybitmessage.readthedocs.io/en/v0.6/autodoc/pybitmessage.api.html <br>
https://wiki.bitmessage.org/index.php/API_Reference 


### TODO
- [ ] Composer Package

### Changelog
- [x] Project Released
