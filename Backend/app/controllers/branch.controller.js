const db = require("../models");
const Branch = db.branches;
const Campaign_Branch_Association = db.campaign_branch_associations;
const Op = db.Sequelize.Op;

// Create and Save a new Branch
exports.create = (req, res) => {
    // Validate request
    if (!req.body.name) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
  
    // Create a branch
    const branch = {
      name: req.body.name,
      business_type: req.body.business_type,
      store_type: req.body.store_type,
      brand: req.body.brand,
      address: req.body.address,
      city: req.body.city,
      region: req.body.region,
      province: req.body.province,
      latitude: req.body.latitude,
      longitude: req.body.longitude,
      photo_url: req.body.photo_url,
      merchant_id: req.body.merchantid

    };

    console.log(branch)
  
    // Save Branch in the database
    Branch.create(branch)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Branch."
        });
      });
  };


  exports.createMany = (req, res) => {
    // Validate request
    if (!req.body.branches) {
      res.status(400).send({
        message: "Content can not be empty!"
      });
      return;
    }
    
    branches_container = req.body.branches;
    branches_container.forEach((element)=> {
      element.merchant_id = req.body.merchantid;
    })


    console.log(branches_container)
  
    // Save Branch in the database
    Branch.bulkCreate(branches_container)
      .then(data => {
        res.send(data);
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while creating the Branch."
        });
      });
  };

// Retrieve all Branches from the database.
exports.findAll = (req, res) => {
  const id = req.body.merchantid;
  var condition = req.query;
  
  if(req.body.merchantid){
    condition.merchant_id = id;
  }
  console.log(condition)
  Branch.findAll({ where: condition , order: [["createdAt", "DESC"]]})
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

// Retrieve all Branches from the database.
exports.listAllPaginate = (req, res) => {
  const id = req.body.merchantid;
  var condition = req.query
  console.log(condition)
  var page_number = 1;
  var count_per_page = 25;
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
  Branch.findAndCountAll({ offset:skip_number_of_items, limit: count_per_page, where: condition , order: [["createdAt", "DESC"]]})
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

// Delete all Branchs from the database.
exports.deleteAll = (req, res) => {
    Branch.destroy({
      where: {},
      truncate: false
    })
      .then(nums => {
        res.send({ message: `${nums} Branchs were deleted successfully!` });
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while removing all Branchs."
        });
      });
  };

  // Retrieve all Branches from the database.
exports.findDistinctFilters = (req, res) => {
  const id = req.body.merchantid;


  var condition = null;
  if(req.body.merchantid){
    condition = {merchant_id: id};
  }
  console.log(condition)

  var chainedPromises = [];
  
  var columns = ['region','business_type',]
  var result_data = {
    region:[],
    business_type:[],
    store_type:[],
    brand:[],
    city:[],
    region:[],
    province:[]
  }

  db.sequelize.transaction({autocommit:false},transaction => {

  Object.keys(result_data).forEach((element)=> {
    chainedPromises.push(
      Branch.findAll({
        where: {
            merchant_id: id
        }, 
        attributes: [[db.Sequelize.literal(`DISTINCT \`${element}\``), element], element],
        transaction
      })
      .then(data => {
        data.forEach((item)=>{result_data[element].push(item[element])})
      })
      .catch(err => {
        res.status(500).send({
          message:
            err.message || "Some error occurred while retrieving filter values."
        });
      })

    
    );

  })
  //console.log(chainedPromises)
  return Promise.all(chainedPromises)
    .then(data => {
          res.send(result_data)
        })
        .catch(err => {
          res.status(500).send({
            message:
              err.message || "Some error occurred while retrieving Filters."
          });
        });
  })


};

