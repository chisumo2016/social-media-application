#  DEVOPS 01
    https://www.youtube.com/watch?v=Ou9j73aWgyE&list=PLdpzxOOAlwvIKMhk8WhzN1pYoJ1YU8Csa
    What is Devops
        is culture that improve the deliivery to ur orgazation .
        eg goal :Improving Delivery and  to ensure , 
                 Automation 
                 Quality 
                 Monitoring
                 Testing 
            example.com -> 10 days (v1-v2) long
            Delivery  within hrs
    
        Is the a process of improving the application delivery /script by ensuring that the  is 
                 Proper Automation 
                 Quality 
                 continous &  Monitoring 
                 continous Testing 
        2week -> 1hrs

        No manual delivery.


    Why Devops as come to picture
        eg Developer (write code) -> example.com -> Customer
            1ors
            System Admin            - building tthe server ->somone has install application
            Buillder Realise Eng  -  Deplloy to the saver  ,  application                
            Server Admin  create App Saver
                
            APP    --------------- Prod

        The abbove was manual , working together to delivery the application , process was bit slow.

    To improve this prove process of delivery.
            . 

    How to introduce yourself (day to day activities)
        I am devop eng with 4-5 yrs  exprience ,before that i was working as Lavarel developer .
        Explain roles andd responnsibilites
            .Automation
            .ensure the quality 
            .ensure i have setup the continuering monitoring
            .Testing te aapplication

        Talking about tools annd technology
                githun
                Kubenetes
                Teleform

    What are your Day-Day Activities 


#  DEVOPS 02  : SDLC WITH DEVOPS
    WHY?
        Standard w/c follow by each organization
        Is the process/ standart  that  followed by organisation / company .
                Design
                Devolop
                Test
        End goal deliver the high quality product .

        e.g
            example.com ->delivery E-commerce application
                                    EG Features Kids
                Different phase before we deliver the product  to  the customer
                EG Features Kids  . 
                    Planning
                        . Gather the requirements  Business Analyst /PO 
                            Interest are of kids section
                        . get the information from the customer
                    Defining
                        Items of documents , write  software sspeciacation requiremment
                    Designing
                        High level design  : SCALABLE  , database
                        L level design     :  let use this particular model , java ,php,sql 

                    Building
                    Testing
                    Deploy

        Devops can bring Automation
                    Building
                    Testing
                    Deploy
        How the devops can improve the  efficiency of these phase
        BUILDING PHASE(Develloper)
            . Developing  on favorite language
            . Write the code and push in common place(GIT)
            . Git 
            . Reviewed wiith PR Member

        TESTING PHASE(QAE)
            . Testing , code will be taken inn git and stored iin the server
            . QA team (Assurance Eng) taking care the software
            .
        DEPLOYMENT PHASE
            .  Putting the  production server
            .  Customer

        Where DOPS comes to this>? Improving the organization effieciency 
            FASTENING THE CODE QUICKLY
            NO MANUALLY INTEVENTION
            
        DEVOPS
        FASTENING PROCESS  --------- QUICKLY (code) ---------AUTOMATION --- EFFICIENCY OF PRODUCT DELIVERY
        WORK TOGETHER
        EVERYTHING HAPPENING IN AUTOMATION
                .  BUILDING
                .  TESTING
                .  DEPLOYMENT


#  DEVOPS 03 : VIRTUAL MACHINE PART-1 
        eg Plain Land  -> Build the House->Living(enjoying)  unffiency
                        . Still using the all land , might require 1/2 acre
                        . u build another portion of ur land ->Rent  .This effiency
                        . One person living with u ans no interference .
                        . VM comes into pic
        What  is a Server
            . Deloveper -local machine 
            .100G Server . Access 
                APP 4GB RAM 
                2 Team
                3 Team
                4 Team
            INFEFFIENCY
        
        Physical VS Virtual
            e.g HP /IBM

            Server -- Team
                
        Hypervisor
            . Software 
            . Logic isolation
                    vm1,vm2,vm3 
                EFFIENCY by Hypervisor
                    vmaware
                    xen
                YYou do logical separation

        it funnction as physical machine as own cpu, memory and hardware .
            Hypervisor is doing everything
        Cloud platform eg amazon , google  build data center eg Tanzania 
        The install the physical Server,put in data center
            . You can VM like Tanzania or Instance 
            .Will sennt into physcal center
            .Create a VM 


        DATA CENTER (AWS) Singapore, Mumbai,USA,UK
            . Select nearest post
            .Install mltiiple ranks
                    ps1 ------p1s00
        AWS - requuest the HYPERS
              SEND THE IP to access  the VM


        How to create VMM
        Real World example
        
#  DEVOPS 04  AWS AND AZURE  HOW TO CREATE VIRTUAL MACHINES PART-2
    Send the requrest to  cloud provide
        AWS
            Developer open the awas .log in aws console (UI) ->AWS EC2
            Send the IP 
        AZURE
            Process is the same 
            Open azure  portal 
            Create the account annd send the IP and other information

    eg 100 - EFFIENCY OF ROUTINE TASK
             AUTOMATION
                AWS EC2 API 

        Dev eng - he can write the  call to make a call to aws ec2 API
               We call automation, no manual 
        Script caan be done in multiple way
            ecs -> developer -> api
            api - receive the request (valid, authenticate, authorized) 
            api - send thhe  

        SCRIPT
            AWS CLI
            AWS API (BOBO3)
            AWS CFT CLOUD IINNFORMATTION TEMPLATE 
            Take to API
            TOOL TERRAFORM - Automation, automate in azure , aws, open source
                .Hybrid Cloud
            AWS CDK -more  advance
        example

                googel aws console 
        https://aws.amazon.com/free/?trk=ce1f55b8-6da8-4aa2-af36-3f11e9a449ae&sc_channel=ps&ef_id=Cj0KCQiAqsitBhDlARIsAGMR1RiDVq9tktOk5OE9WoN_jP7_6n0Q2_CrsDhJJXCi7AgSLZo6v4b4pggaAlleEALw_wcB:G:s&s_kwcid=AL!4422!3!645997746383!e!!g!!aws%20console!9762827897!98496538543
         Select  ec2 sevice
#  DEVOPS 05 HOW TO CONNECT  TO EC2 INSTANCE WINDOWS LAPTOP
        - Click Launc Instances
        - Prove name winow
        - sselct ubuntu
        - Select free
        - Create new key pair
                . provide name eg window-demo
                .Key pair type
                    RSA
                .Private key file format
                    .pem

        . Click CREATE KEy PAIR
        .Check Network settings - ok
        - Download MOBAXTERM
#  DEVOPS 06 AWS FULL GUIDE | HOW TO CONNECT TO EC2 INSTANCE FROM UI AND TERMINAL  | AWS CTF WALK THROUGH
       1 . AWS console
        .Terminall on ur mac
            public IP
            ssh ubuntu@ip address
            ssh -i /Users/Downods/test1.pem ubuntu@ip-address
            ssh chmod 600 /Users/Downods/test1.pem 
            ssh -i /Users/Downods/test1.pem ubuntu@ip-address

        Successfully cli innstance

    3: AWS CLI 
        https://aws.amazon.com/cli/
        - profile-securitty credenntial->Access key -> create access key ->Secret access key & acess key 

            $ aws configure press entter key
               AWS Access Key IDD :
               AWS Secret  Access Key :

        $ aws s3 ls
        $ aws s3 mb bernard1234455
            https://docs.aws.amazon.com/cli/latest/userguide/cli-services-ec2-instances.html

      4: CLOUD INFOMATIONN TEMPLATE
        https://github.com/awslabs/aws-cloudformation-templates
        https://github.com/awslabs/aws-cloudformation-templates/blob/master/aws/services/EC2/EC2InstanceWithSecurityGroupSample.yaml
            Steps
                TYPE CFT ->cloud information ->Create stack ->Template is ready ->Amozn s3 URL

      3: AWS API
            
#  DEVOPS 07 LINUX AND SHELL SCRIPTING | COMPLETE SHELL SCRIPTING PLAYLIST
     Linux Operatting System and Basic of shell scripting
            Kernel -  
                        Device management
                        Memory management
                        Process management
                        Handling system
            System Libraries - Perfoming tasks

    SHELL SCRIPTING 
        .create  a file 
        pwd
        cd - change directory
        ls - list files annd directory
        cd  .. one directory
        cd  .../..  two directory
        ls -ltr  lisst files and directoryy with  its property
        touch  : create a file
        vi - write and fiel
        cat test - print the  value
        mkdir  making directory
        rm  removing file
        rm -r  removing file and direcctory
                checking CPU memory and disk size
        
        
        

#  DEVOPS 08
#  DEVOPS 01
#  DEVOPS 01
#  DEVOPS 01
#  DEVOPS 01
#  DEVOPS 01
    
