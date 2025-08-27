#!/bin/bash

pids=$(ls /proc | grep [0-9])
cgroupusers=$(cat /var/webuzo/conf/cgroupusers)
declare -A users
for uid in $cgroupusers; do
    uids[$uid]=1
done

while sleep 5; do
	newpids=$(ls /proc | grep [0-9])
	diff=$(grep -Fxv "${pids[*]}" <(printf '%s\n' ${newpids[@]}))
	for pid in ${diff[@]}; do
		if [ -d /proc/$pid ]; then
			uid=$(ps -o uid= -p $pid | awk '{$1=$1;print}')
			if [ ${uids[$uid]} ]; then				
				echo $pid >> "/sys/fs/cgroup/"$uid".slice/cgroup.procs"
			fi
		fi
	done
	pids=$newpids
done