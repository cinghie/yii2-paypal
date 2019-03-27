<?php

namespace cinghie\paypal\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use cinghie\paypal\models\Payments;

/**
 * PaymentsSearch represents the model behind the search form of `cinghie\paypal\models\Payments`.
 */
class PaymentsSearch extends Payments
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'user_id', 'created_by'], 'integer'],
            [['transaction_id', 'payment_id', 'client_token', 'payment_method', 'currency', 'payment_state', 'method', 'description', 'created'], 'safe'],
            [['total_paid'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Payments::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'total_paid' => $this->total_paid,
            'created_by' => $this->created_by,
            'created' => $this->created,
        ]);

        $query->andFilterWhere(['like', 'transaction_id', $this->transaction_id])
            ->andFilterWhere(['like', 'payment_id', $this->payment_id])
            ->andFilterWhere(['like', 'client_token', $this->client_token])
            ->andFilterWhere(['like', 'payment_method', $this->payment_method])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'payment_state', $this->payment_state])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
