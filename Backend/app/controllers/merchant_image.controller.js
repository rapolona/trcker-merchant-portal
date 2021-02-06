const db = require("../models");
const Merchant_image = db.merchant_images;
const s3Util = require("../utils/s3.utils.js");
const moment = require("moment");
const Op = db.Sequelize.Op;

// Create and Save a new Branch
exports.create = (req, res) => {
    // Validate request
    if (!req.body.merchant_image_file_name ||!req.body.merchant_image_base64 ) {
      res.status(400).send({
        message: "File name or base64 missing"
      });
      return;
    }
  
    // Create a branch
    const merchant_image = {
      label: req.body.label,
      file_name: req.body.merchant_image_file_name,
      merchant_id: req.body.merchantid
    };

    console.log(merchant_image)
    var chainedPromises = [];
    // Save Merchant image in the database

    db.sequelize.transaction(transaction =>
    Merchant_image.create(merchant_image, transaction)
      .then(data => {
        //res.send(data);
        const now = moment().format('XX')
        var merchant_image_file_name = "MerchantImage_"+data.merchant_image_id+"_"+ now+"_"+req.body.merchant_image_file_name
        chainedPromises.push(
          s3Util.s3Upload(req.body.merchant_image_base64, "MerchantGalleryImages"+"/" + merchant_image_file_name, "dev-trcker-merchant-images",{})
          .catch(err=>{
            transaction.rollback()
            console.log("Error uploading to S3" + " "+ err.message)
            res.status(500).send({
              message: err.code || "Error uploading image to s3"
            })
          }))

          chainedPromises.push(
            Merchant_image.update({file_name: merchant_image_file_name}, {
              where: { merchant_image_id: data.merchant_image_id }, transaction
            })
              .then(num => {
                if (num == 1) {
                  //res.send(data);
                } else {
                transaction.rollback()
                  res.status(422).send({
                    message: `Cannot update Merchant Image with id=${data.merchant_image_id}. Maybe Merchant was not found or req.body is empty!`
                  });
                }
              })
              .catch(err => {
                transaction.rollback()
                res.status(500).send({
                  message: err.code+" Error updating Merchant Image with id=" + data.merchant_image_id
                });
              })
    
          )
          return Promise.all(chainedPromises)
          .then(newdata=> {
            res.send("Successfully uploaded image")
          })
          .catch(err => {
            console.log("Error getting Merchant Images")
          })




      })
      .catch(err => {
        transaction.rollback();
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Merchant Image."
        });
      })
    )
  };






// Retrieve all Branches from the database.
exports.listAllPaginate = (req, res) => {
  const id = req.body.merchantid;
  var condition = req.query
  console.log(condition)
  var page_number = 1;
  var count_per_page = null;
  if((req.query.page)&&(req.query.count_per_page)){
    var page_number = parseInt(req.query.page);
    var count_per_page = parseInt(req.query.count_per_page);
    delete req.query.page
    delete req.query.count_per_page
  } 

  var skip_number_of_items = (page_number * count_per_page) - count_per_page;
  
  if(req.body.merchantid){
    condition.merchant_id = id;
  }
  console.log(condition)
  var chainedPromises = []
  Merchant_image.findAndCountAll({ offset:skip_number_of_items, limit: count_per_page, where: condition , order: [["createdAt", "DESC"]]})
    .then(data => {
        var new_result = {}
        new_result.count = data.count
        new_result.rows = []

        data.rows.forEach(element=> {
            new_element = element.get({plain:true})
            if(element.file_name){
                if(element.file_name.startsWith("MerchantImage_")){
                chainedPromises.push(s3Util.s3getHeadObject("dev-trcker-merchant-images", "MerchantGalleryImages/"+element.file_name)
                .then(new_data => {
                    var signedThumbnailImageURL = s3Util.s3GetSignedURL("dev-trcker-merchant-images", "MerchantGalleryImages/"+element.file_name)
                    new_element.signed_url = signedThumbnailImageURL
                    new_result.rows.push(new_element)
                    console.log(signedThumbnailImageURL)
                })
                .catch(err => {
                    res.status(500).send({
                    message: err.code 
                    });
                }))
                }
            }
    
          })
          return Promise.all(chainedPromises)
          .then(newdata=> {
            res.send(new_result)
          })
          .catch(err => {
            console.log("Error getting Merchant Images")
          })

    })
    .catch(err => {
      res.status(500).send({
        message:
          err.message || "Some error occurred while retrieving Merchant Images."
      });
    });
};

// Find a single Branch with an id
exports.findOne = (req, res) => {
    const branch_id = req.params.branch_id;
    const merchant_id = req.body.merchantid;

  
    Branch.findByPk(branch_id)
      .then(data => {
        console.log(data.merchant_id)
        if(data.merchant_id==merchant_id){
          res.send(data);
        }
        else{
          res.status(422).send({
            message: "Error retrieving Branch with id=" + branch_id + ". Branch does not belong to merchant."
          });
        }
        
      })
      .catch(err => {
        res.status(500).send({
          message: "Error retrieving Branch with id=" + branch_id
        });
      });
  };

// Update a Branch by the id in the request
exports.update = (req, res) => {
    const branch_id = req.body.branch_id;
  
    Branch.update(req.body, {
      where: { branch_id: branch_id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Branch was updated successfully."
          });
        } else {
          res.status(422).send({
            message: `Cannot update Branch with id=${id}. Maybe Branch was not found or req.body is empty!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Error updating Branch with id=" + id
        });
      });
  };

// Delete a Branch with the specified id in the request
exports.delete = (req, res) => {
    const id = req.body.branch_id;
    const merchant_id = req.body.merchantid;
  
    Branch.destroy({
      where: { branch_id: id, merchant_id: merchant_id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Branch was deleted successfully!"
          });
        } else {
          res.status(422).send({
            message: `Cannot delete Branch with id=${id}. Maybe Branch was not found!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Could not delete Branch with id=" + id
        });
      });
  };





