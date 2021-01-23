const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Province = sequelize.define("province", {
      Id:{
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true,
        unique:true
      },
      Psgccode: {
        type: Sequelize.STRING(64),
        allowNull: false
      },
      label: {
        type: Sequelize.STRING(255),
        unique:true,
        allowNull: false
      },
      Regcode: {
        type: Sequelize.STRING(64),
        allowNull: false
      },
      Provcode: {
        type: Sequelize.STRING(64),
        allowNull: false
      }
    });
    return Province;
  };