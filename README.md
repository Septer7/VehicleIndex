# Vehicle Web API (Web Services Project)

Vehicle API allows users to retrieve various information regarding a certain type of vehicle, including Vin Decoding as well as Recall Data.
This is the repository for the group project of the **"TBD"** team for WEB SERVICES W23 at Vanier College. It includes:

- All files (Project File, Images)
- Documentation

## [Tasks Backlog](https://github.com/users/Septer7/projects/4/views/1)
This link contains the GitHub Project associated with our game containing the Status of all our tasks as well as the Person assigned to that specific task.

## Deployment Instructions
1. Clone the repository folder into `htdocs` under the root folder name `vehicle-api`.
2. Import the DB the from folder [data](/data) find `car_emissions.sql`.
3. In terminal, run `composer update`.
4. In your xampp Control Panel, run Apache and MySql.
5. In Thunder Client go to [Post] 'http://localhost/vehicle-api/account'
    Body'
        [{
          "email":"veaci@gmail.com",
          "first_name":"Veaci",
          "last_name":"Vlas",
          "password":"121212",
          "role":"admin"
        }]
    '
    note: it must be insite a map [], done for creating multiple accounts at once.
    
 6. In Thunder Client go to [Post] 'http://localhost/vehicle-api/token'   
    Body'    
        {
          "email":"veaci@gmail.com",
          "password":"121212",
          "role":"admin"
        }
    '
     note: it must be insite a simple array {}.
  7. In Thunder Client go to Auth->Bearer and put the token from above step in order to get access to api's resources.
  8. Note: in order to use the PUT, POST, DELETE methods, an user must have "role" : "admin"   

## [Deliverables](/Deliverables)
This folder contains the files and folders that are required every couple of weeks from our teacher.

## [Assets](/Assets)
This folder contains the text content, graphics, photographs, videos, audio files, and databases..

## Copyright and License

Copyright 2023. Code released under the [MIT](https://github.com/Septer7/VehicleIndex/blob/main/LICENSE) license.

## Team Members
- [Adam Adrian Moosa](https://github.com/Septer7)
- [Gcarlosviza](https://github.com/Gcarlosviza)
- [ValentinG1](https://github.com/ValentinG1)
- [Veaceslav Vlas](https://github.com/vlasslavic)
- [Tommy Rivard](https://github.com/triv117)
