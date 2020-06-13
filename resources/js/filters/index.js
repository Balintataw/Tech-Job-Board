import formatDistanceToNow from 'date-fns/formatDistanceToNow'
import format from 'date-fns/format'

export const dateFormatTimeAgo = (value) => {
  if (value) {
    return formatDistanceToNow(new Date(value), { addSuffix: true })
  }
};

export const dateFormat = (value) => {
  if (value) {
    return format(new Date(value), 'MMM dd, YYY')
  }
};