const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Branch = sequelize.define("branch", {
      branch_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      name: {
        type: Sequelize.STRING(64),
        unique:true,
        allowNull: false
      },
      address: {
        type: Sequelize.STRING(255)
      },
      city: {
        type: Sequelize.STRING(64)
      },
      latitude: {
        type: Sequelize.STRING(64),
        validate: {
          isFloat: true
        }
      },
      longitude: {
        type: Sequelize.STRING(64),
        validate: {
          isFloat: true
        }
      },
      photo_url: {
        type: Sequelize.TEXT
      },
      merchant_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // Branches belongsTo Merchant N:1
          model: 'merchants',
          key: 'merchant_id'
        }  
      }
    });
    return Branch;
  };