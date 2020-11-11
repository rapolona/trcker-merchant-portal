const db = require("../models");
const Product = db.products;
const Op = db.Sequelize.Op;

// Create and Save a new Product
exports.create = (req, res) => {
    // Validate request
    if (!req.body.product_name) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
  
    // Create a product
    const product = {
      product_name: req.body.product_name,
      product_description: req.body.product_description,
      merchant_id: req.body.merchantid
    };

    console.log(product)
  
    // Save Product in the database
    Product.create(product)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Product."
        });
      });
  };

// Retrieve all Products from the database.
exports.findAll = (req, res) => {

  const id = req.body.merchantid;
  var condition = req.query;
  
  if(req.body.merchantid){
    
    condition.merchant_id = id;
  }

  Product.findAll({ where: condition , order: [["createdAt", "DESC"]]})
    .then(data => {
      res.send(data);
    })
    .catch(err => {
      res.status(500).send({
        message:
          err.message || "Some error occurred while retrieving products."
      });
    });
};

// Find a single Product with an id
exports.findOne = (req, res) => {
  const product_id = req.params.product_id;
  const merchant_id = req.body.merchantid;
  
    Product.findByPk(product_id)
      .then(data => {
        if(data.merchant_id==merchant_id){
          res.send(data);
        }
        else{
          res.status(422).send({
            message: "Error retrieving Product with id=" + product_id + ". Product does not belong to merchant."
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Error retrieving Product with id=" + product_id
        });
      });
  };

// Update a Product by the id in the request
exports.update = (req, res) => {
    const product_id = req.body.product_id;
  
    Product.update(req.body, {
      where: { product_id: product_id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Product was updated successfully."
          });
        } else {
          res.status(422).send({
            message: `Cannot update Product with id=${id}. Maybe Product was not found or req.body is empty!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Error updating Product with id=" + id
        });
      });
  };

// Delete a Product with the specified id in the request
exports.delete = (req, res) => {
    const product_id = req.body.product_id;
    const merchant_id = req.body.merchantid;
  
    Product.destroy({
      where: { product_id: product_id, merchant_id: merchant_id }
    })
      .then(num => {
        if (num == 1) {
          res.send({
            message: "Product was deleted successfully!"
          });
        } else {
          res.status(422).send({
            message: `Cannot delete Product with id=${id}. Maybe Product was not found!`
          });
        }
      })
      .catch(err => {
        res.status(500).send({
          message: "Could not delete Product with id=" + id
        });
      });
  };

// Delete all Merchants from the database.
exports.deleteAll = (req, res) => {
    Product.destroy({
      where: {},
      truncate: false
    })
      .then(nums => {
        res.send({ message: `${nums} Merchants were deleted successfully!` });
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while removing all Merchants."
        });
      });
  };

