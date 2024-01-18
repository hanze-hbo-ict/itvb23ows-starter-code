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

        stage('prune docker data') {
            steps {
                sh 'docker system prune -a --volumes -f'
            }
        }

        stage("start container") {

            steps {
                sh 'docker compose up -d --wait'
                sh 'docker compose ps'
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
            sh 'docker compose down --remove-orphans -v'
            sh 'docker compose ps'
            deleteDir()
        }
    }
}