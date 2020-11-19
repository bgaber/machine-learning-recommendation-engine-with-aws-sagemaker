# Machine Learning Recommendation Engine with AWS SageMaker
![Alt text](images/CloudGuruChallenge.jpg?raw=true "Machine Learning Recommendation Engine with AWS SageMaker")

Goal
----
The goal of this project was to build a Netflix style recommendation engine with AWS SageMaker and other ML tools.

Typically, in any Machine Learning project the Data Preparation phase takes up about 70% of the project time and this proved true for me in this project.  The majority of my time was spent in understanding the IMDb sourced data, cleaning it and preparing it.

Project Description
-------------------
Using IMDb Datasets, AWS SageMaker, Jupyter hosted notebook, Python data science libraries and exploring matplotlib, scikit-learn and the k-means learning algorithm I created a Netflix Style Recommendation Engine.

Main Steps
----------
1. Create Jupyter hosted notebook

To start the data inspection process, I launched a Jupyter hosted notebook on Amazon SageMaker. I used Python and various data science libraries like NumPy and Pandas’ DataFrame to work with the IMBd data.

2. Inspect and visualize data

It was important to gain domain knowledge of the IMDb data so that I could easily detect anomalies and outliers. I used Matplotlib for this step.

3. Prepare and transform data

The next step was to put the data in a format a machine can learn from. Transforming included combining disjointed data files into one, removing null values, converting strings to numbers, or performing some feature engineering.

4. Train

Once the data was prepared, the training process began using the selected machine learning algorithm. The algorithm clustered or grouped the IMDb data in order to make recommendations. The plan was to use the k-means clustering algorithm.  I considered using two providers: Amazon SageMaker provides a k-means clustering algorithm and so does scikit-learn.  I was successful with Amazon SageMaker and so I did not explore scikit-learn's version.

5. Recommend

With clusters identified, it was possible to recommend movies by analyzing the clusters to find commonalities. Once commonalities were understood, I was able to find other movies that were similar to recommend.

Implemented Architecture
------------------------
The diagram below depicts the architecture I implemented.  Just to be clear all the Machine Learning elements (Jupyter Notebook, SageMaker, etc) to the left of the VPC took up about 90% of the work.  The VPC, webserver, database server was created by a CloudFormation template.
![Alt text](images/ml-movie-recommendations.png?raw=true "Implemented Machine Learning Architecture")


