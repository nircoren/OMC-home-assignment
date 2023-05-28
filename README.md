# OMC-home-assignment

A php and mysql project. 
the app takes data from Bank Of Israel API and presents it as an API and in a graphic format.
It has an api that takes the data from the mysql DB and presents it in JSON format.

https://github.com/Nircoren1/OMC-home-assignment/assets/114494002/58214bc3-24fd-46f9-a3dd-5c17c4adc3c0

Graphic Mode
To see the data in graphic mode:
1. Click the "Get Data From BOI". This will send the data to the mysql DB,
2. Type a number, every value of currency above it will be marked (optional).
3. Pick which dates will be presented (optional).
4. Choose which types of currency exchanges you want to see.
5. Click on "Show obs" and the table will be presented.


https://github.com/Nircoren1/OMC-home-assignment/assets/114494002/d68e0ddd-4452-4ff9-92d2-13285b313200


API
To use the api you type in the query params in this format: http://localhost/omcapp/api/XXX_to_ILS?startperiod=XXXX-XX-XX&endperiod=XXXX-XX-XX
The api supports USD,EUR and GBP. After you type, you will get the data in JSON format.

