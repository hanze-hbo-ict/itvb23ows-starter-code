pipeline {

    agent any

    stages {

        stage("build") {

            steps {
                sh 'echo "building"'
                sh 'docker-compose build'
                sh 'docker-compose up'
            }

        }

        stage("test") {

            steps {
                sh 'echo "testing"'
            }

        }

        stage("deploy") {

            steps {
                sh 'echo "deploying"'
            }

        }
    }
    post {
        always {
            deleteDir()
        }
    }
}