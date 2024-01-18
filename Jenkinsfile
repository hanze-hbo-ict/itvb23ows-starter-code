pipeline {

    agent any

    stages {

        stage("verify tooling") {
            steps {
            sh '''
                docker info
                docker version
                docker compose version
                '''
            }
        }

        stage("build") {

            steps {
                sh 'echo "building"'
                sh 'docker compose build'
                sh 'docker compose up'
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