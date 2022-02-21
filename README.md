# Laravel Loan Management Process

It is a project for demonstrating of loan applicaltion 

## Tech

- Laravel 8, PHP 8.0 (uses enums)

## How to use

- Clone the repository with __git clone__
- Copy __.env.example__ file to __.env__ and edit database credentials there
- Run __composer install__
- Run __php artisan key:generate__
- Run __php artisan migrate__ 
- Run __php artisan serve__ 


## Postman API

To check Postman API, [click here.](https://www.postman.com/grey-moon-872627/workspace/public-aspire)



## API Collection
 
- [Postman Collection](https://drive.google.com/file/d/16z-W0f16WcnwQIzLiFyslmmPX6E5ILXe/view)
 
## Instructions on testing with Postman

- Register a user first. This will automatically login the user
- After registration customer needs to complete KYC verification
- After completing KYC verification customer can apply for loan
- Apply for loan and get it approved by admin.
- Once admin approve loan customer can repay installments

