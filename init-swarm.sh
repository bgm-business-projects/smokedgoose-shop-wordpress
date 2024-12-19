#!/bin/bash
MANAGER_IP="10.140.0.3"  # 或公網 IP

# 初始化 Swarm，使用有效 IP
docker swarm init --advertise-addr $MANAGER_IP

# 輸出 Token
echo "Swarm join token for workers:"
docker swarm join-token worker