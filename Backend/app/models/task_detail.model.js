const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Task_Detail = sequelize.define("task_detail", {
      task_detail_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      response: {
        type: Sequelize.TEXT('long'),
        allowNull: false
      },
      ranking: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      image_source: {
        type: Sequelize.STRING(64)
      },
      file_name: {
        type: Sequelize.TEXT('long')
      },
      task_ticket_id: {
        type:DataTypes.UUID,
        allowNull:false,
        references: {
          model:"task_tickets",
          key:"task_ticket_id"
        }
      }
    });
    return Task_Detail;
  };