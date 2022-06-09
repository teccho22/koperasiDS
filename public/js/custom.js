var whatIsIt;

function relocate(link)
{
	window.location.href = link;
}

function setMobileLayout(id)
{
	if ($(window).width() < 1280)
	{
		var width = 1280;
		if ($(id).parent().parent().parent().width() >= 1280 && $(id).parent().parent().parent().width() <= 1366)
		{
			width = 1366;
		}
		else if ($(id).parent().parent().parent().width() >= 1366 && $(id).parent().parent().parent().width() <= 1920)
		{
			width = 1920;
		}

		$(id).parent().parent().parent().css('width', width);
	}
}

function dateKeypress(event)
{
	if (event.keyCode == 35 /*Home*/ || event.keyCode == 36 /*End*/ || event.keyCode == 37 /*Left*/ || event.keyCode == 39 /*Right*/ || event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || event.which == 8 /*Backspace*/ || (event.charCode >= 48 && event.charCode <= 57) /*0-9*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function subclassKeypress(event)
{
	if (event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || (event.charCode >= 65 && event.charCode <= 90) /*A-Z*/ || (event.charCode >= 97 && event.charCode <= 122) /*a-z*/ || event.keyCode == 35 /*Home*/ || event.keyCode == 36 /*End*/ || event.keyCode == 37 /*Left*/ || event.keyCode == 39 /*Right*/ || event.which == 8 /*Backspace*/ || (event.charCode >= 48 && event.charCode <= 57) /*0-9*/ || event.charCode == 47 /*/*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function numericKeypressVarNumber(event)
{
	if (event.keyCode == 9 /*Tab*/ || (event.charCode >= 48 && event.charCode <= 57) /*0-9*/ || event.keyCode == 35 /*Home*/ || event.keyCode == 36 /*End*/ || event.which == 8 /*Backspace*/ || event.which == 46 /*.*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function numericKeypress(event)
{
	if (event.charCode >= 48 && event.charCode <= 57 /*0-9*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function numericKeypressAndMinus(event)
{
	if (event.charCode >= 48 && event.charCode <= 57 /*0-9*/ || event.charCode == 45 /* Minus (-) */)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function numericKeypressAndDot(event)
{
	if (event.charCode >= 48 && event.charCode <= 57 /*0-9*/ || event.charCode == 46 /* Dot (.) */ )
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function doubleKeypress(event)
{
	if (event.keyCode == 9 /*Tab*/ || (event.charCode >= 48 && event.charCode <= 57) /*0-9*/ || event.which == 8 /*Backspace*/ || event.charCode == 46 /*.*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function doubleKeypressWithMinus(event)
{
	if (event.keyCode == 9 /*Tab*/ || (event.charCode >= 48 && event.charCode <= 57) /*0-9*/ || event.which == 8 /*Backspace*/ || event.charCode == 46 /*.*/ || event.charCode == 45 /* Minus (-) */)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function decimalKeypress(event)
{
	if (event.keyCode == 9 /*Tab*/ || (event.charCode >= 48 && event.charCode <= 57) /*0-9*/ || event.keyCode == 35 /*Home*/ || event.keyCode == 36 /*End*/ || event.which == 8 /*Backspace*/ || event.charCode == 46 /*.*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function decimalWithMinusKeypress(event)
{
	if (event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || (event.charCode >= 48 && event.charCode <= 57) /*0-9*/ || event.keyCode == 35 /*Home*/ || event.keyCode == 36 /*End*/ || event.keyCode == 37 /*Left*/ || event.keyCode == 39 /*Right*/ || event.which == 8 /*Backspace*/ || event.charCode == 46 /*.*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if (event.charCode == 45) /* - */
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function alphabetKeypress(event)
{
	if (event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || (event.charCode >= 65 && event.charCode <= 90) /*A-Z*/ || (event.charCode >= 97 && event.charCode <= 122) /*a-z*/ || event.keyCode == 35 /*Home*/ || event.keyCode == 36 /*End*/ || event.keyCode == 37 /*Left*/ || event.keyCode == 39 /*Right*/ || event.which == 8 /*Backspace*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function alphabetKeypressOperator(event)
{
	if (event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || (event.charCode >= 65 && event.charCode <= 90) /*A-Z*/ || (event.charCode >= 97 && event.charCode <= 122) /*a-z*/ || event.keyCode == 39 /*Right*/ || event.which == 8 /*Backspace*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function alphabetKeypressOperatorWithSpace(event)
{
	if (event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || (event.charCode >= 65 && event.charCode <= 90) /*A-Z*/ || (event.charCode >= 97 && event.charCode <= 122) /*a-z*/ || event.keyCode == 39 /*Right*/ || event.which == 8 /*Backspace*/ || event.which == 32 /*Backspace*/ )
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function alphabetKeypressSa(event)
{
	if (event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || (event.charCode >= 65 && event.charCode <= 90) /*A-Z*/ || (event.charCode >= 97 && event.charCode <= 122) /*a-z*/ || event.which == 8 /*Backspace*/ || event.which == 32 /*Backspace*/ )
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function alphabetWhitespaceKeypress(event)
{
	if (event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || (event.charCode >= 65 && event.charCode <= 90) /*A-Z*/ || (event.charCode >= 97 && event.charCode <= 122) /*a-z*/ || event.keyCode == 35 /*Home*/ || event.keyCode == 36 /*End*/ || event.keyCode == 37 /*Left*/ || event.keyCode == 39 /*Right*/ || event.which == 8 /*Backspace*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if(event.charCode == 32)
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function alphaNumericKeypress(event)
{
	if (event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || (event.charCode >= 65 && event.charCode <= 90) /*A-Z*/ || (event.charCode >= 97 && event.charCode <= 122) /*a-z*/ || event.which == 8 /*Backspace*/ || (event.charCode >= 48 && event.charCode <= 57) /*0-9*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function alphaNumericSymbolKeypressSa(event)
{
	if (event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || (event.charCode >= 65 && event.charCode <= 90) /*A-Z*/ || (event.charCode >= 97 && event.charCode <= 122) /*a-z*/ || event.which == 8 /*Backspace*/ || (event.charCode >= 44 && event.charCode <= 57) /*, - . / 0-9*/ || event.shiftKey)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function alphaNumericKeypressOperator(event)
{
	if (event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || (event.charCode >= 65 && event.charCode <= 90) /*A-Z*/ || (event.charCode >= 97 && event.charCode <= 122) /*a-z*/ || event.which == 8 /*Backspace*/ || (event.charCode >= 48 && event.charCode <= 57) /*0-9*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function alphaNumericKeypressWithSlash(event)
{
	if (event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || (event.charCode >= 65 && event.charCode <= 90) /*A-Z*/ || (event.charCode >= 97 && event.charCode <= 122) /*a-z*/ || event.which == 8 /*Backspace*/ || (event.charCode >= 47 && event.charCode <= 57) /*0-9*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function alphaNumericWhitespaceKeypressOperator(event)
{
	if (event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || (event.charCode >= 65 && event.charCode <= 90) /*A-Z*/ || (event.charCode >= 97 && event.charCode <= 122) /*a-z*/ || event.which == 8 /*Backspace*/ || (event.charCode >= 48 && event.charCode <= 57) /*0-9*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if(event.charCode == 32)
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function alphaNumericWhiteSpaceSpecialKeypress(event)
{
	if (event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || (event.charCode >= 65 && event.charCode <= 90) /*A-Z*/ || (event.charCode >= 97 && event.charCode <= 122) /*a-z*/ || event.which == 8 /*Backspace*/ || (event.charCode >= 48 && event.charCode <= 57) /*0-9*/)
	{
		return true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		return true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		return true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		return true;
	}
	else if(event.charCode == 32) /*  */
	{
		return true;
	}
	else if (event.charCode == 45) /* - */
	{
		return true;
	}
	else if (event.charCode == 95) /* _ */
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function baggageTagKeypress(event)
{
	if (event.which == 47 || event.keyCode == 9 || event.keyCode == 116 || (event.keyCode >= 48 && event.keyCode <= 57) || event.keyCode == 35 || event.keyCode == 36 || event.keyCode == 37 || event.keyCode == 39 || event.which == 8)
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}
}

function hotKeyDetect(event)
{
	alert();
	if (event.ctrlKey)
	{
		if ((event.keyCode == 65 || event.keyCode == 97) || (event.keyCode == 67 || event.keyCode == 99) || (event.keyCode == 86 || event.keyCode == 118) || (event.keyCode == 88 || event.keyCode == 120))
		{
			return true;
		}
		else
		{
			event.preventDefault();
		}
	}
	else
	{
		event.preventDefault();
	}
}

function moneyFormat(x)
{
	if (x == null)
	{
		return '0.00';
	}
	else
	{
		if (x.toString().indexOf('.') == -1)
		{
			return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '.00';
		}
		else
		{
			if (x.toString().substr(x.toString().indexOf('.') + 1, 2).length == 1)
			{
				return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '0';
			}
			else
			{
				return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			}
		}
	}
}

function thousandSeparator(x)
{
	if (x == null)
	{
		return '0';
	}
	else
	{
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
}

function checkNull(data)
{
	return data != null ? data : '';
}

function numberFormatter1(value, row, index)
{
	return '#' + (++index);
}

function numberFormatter2(value, row, index)
{
	return (++index) + '.';
}

function isInArray(value, array)
{
	return array.indexOf(value);
}

function checkAllFormatter(value, row, index)
{
	return true;
}

function totalTextFormatter(data)
{
	return 'Total : ';
}

function sumFormatter1(data)
{
	field = this.field;

	var total_sum = data.reduce(function(sum, row)
	{
		return parseInt(sum) + parseInt(row[field] || 0);
	}, 0);

	return '<span id="spanBaggageTotal">' + total_sum + ' kgs</span>';
}

function sumFormatter2(data)
{
	field = this.field;

	var total_sum = data.reduce(function(sum, row)
	{
		return parseInt(sum) + parseInt(row[field] || 0);
	}, 0);

	return '<span id="spanWeightTotal">' + total_sum + ' pcs</span>';
}

function yyyymmddValidator(dates)
{
	var y = dates.substr(0, 4);
	m = dates.substr(4, 2) - 1;
	d = dates.substr(6, 2);

	var D = new Date(y,m,d);

	return (D.getFullYear() == y && D.getMonth() == m && D.getDate() == d) ? 1 : 0;
}

function randomChar(len)
{
	var i;
	var text = '';
	var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	for (i = 0 ; i < len ; i++)
	{
		text += possible.charAt(Math.floor(Math.random() * possible.length));
	}

	return text;
}

function numericKeypressFlightNumber(event,data)
{
	var flag = 0;
	var bool = false;

	if((event.keyCode >= 48 && event.keyCode <= 57))
	{
		flag = 1;
	}

	if(event.which == 8)
	{
		flag = 2;
	}

	if (event.keyCode == 9 /*Tab*/ || event.keyCode == 116 /*F5*/ || event.keyCode == 35 /*Home*/ || event.keyCode == 36 /*End*/ || event.keyCode == 37 /*Left*/ || event.keyCode == 39 /*Right*/)
	{
		bool = true;
	}
	else if ((event.charCode == 65 || event.charCode == 97) && event.ctrlKey) // CTRL + A
	{
		bool = true;
	}
	else if ((event.charCode == 67 || event.charCode == 99) && event.ctrlKey) // CTRL + C
	{
		bool = true;
	}
	else if ((event.charCode == 86 || event.charCode == 118) && event.ctrlKey) // CTRL + V
	{
		bool = true;
	}
	else if ((event.charCode == 88 || event.charCode == 120) && event.ctrlKey) // CTRL + V
	{
		bool = true;
	}
	else
	{
		bool = false;
	}

	if(flag == 1)
	{
		var number = data.value.toString();
		var temp = 0;
		for(var i=0;i<number.length;i++)
		{
			if(number[i].match("[1-9]"))
			{
				break;
			}
			else
			{
				temp++;
			}
		}

		number = number.substr(temp,number.length);

		if(number.length < 4)
		{
			var newNumber = pad(number + String.fromCharCode(event.keyCode),3);

			//Ngecek kalau cuman 0
			if(!newNumber.match("[^0]+"))
			{
				return false;
			}
			else
			{
				data.value = newNumber;
			}
		}
		else
		{
			return false;
		}
	}
	else if(flag == 2)
	{
		var number = data.value.toString();
		var temp = 0;
		for(var i=0;i<number.length;i++)
		{
			if(number[i].match("[1-9]"))
			{
				break;
			}
			else
			{
				temp++;
			}
		}

		number = number.substr(temp,number.length);

		if(number.length <= 1)
		{
			data.value = '';
		}
		else
		{
			var newNumber = pad(number.substr(0,number.length-1),3);
			data.value = newNumber;
		}

	}


	if(bool == true)
	{
		return true;
	}
	else
	{
		event.preventDefault();
	}

}

function pad(str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}


function forceUppercase(data) {
	data.value = data.value.toUpperCase();
}

function accurateCalc(num1, operator, num2) {
	num1 = parseFloat(num1);
	num2 = parseFloat(num2);
	if (isNaN(num1) || isNaN(num2))
	{
		// Values validation
		return Number.NaN;
	}

	var strNum1 = num1 + '',
		strNum2 = num2 + '',
		dpNum1 = !!(num1 % 1) ? (strNum1.length - strNum1.indexOf('.') - 1) : 0, // Get total decimal places of num1
		dpNum2 = !!(num2 % 1) ? (strNum2.length - strNum2.indexOf('.') - 1) : 0, // Get total decimal places of num2
		multiplier = Math.pow(10, dpNum1 > dpNum2 ? dpNum1 : dpNum2), // Compare dpNum1 and dpNum2, then find value of 10 to the power of the largest between them.
		tempNum1 = Math.round(num1 * multiplier), // Multiply num1 by multiplier to eliminate all decimal places of num1.
		tempNum2 = Math.round(num2 * multiplier), // Multiply num2 by multiplier to eliminate all decimal places of num2.
		result;

	switch (operator.trim())
	{
		case '+':
			result = (tempNum1 + tempNum2) / multiplier;
			break;
		case '-':
			result = (tempNum1 - tempNum2) / multiplier;
			break;
		case '*':
			result = (tempNum1 * tempNum2) / (multiplier * multiplier);
			break;
		case '/':
			result = (tempNum1 / tempNum2);
			break;
		case '%':
			result = (tempNum1 % tempNum2) / multiplier;
			break;
		default:
			result = Number.NaN;
	}

	return result;
}

//Change decimal input to 2 decimal places as we type
function validateFloatKeyPress(el, evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode;
	var number = el.value.split('.');

	if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}

	//just one dot
	if(number.length>1 && charCode == 46){
		return false;
	}

	//get the carat position
    var whatBrowser = getIt();

    if (whatIsIt == 'notIE' && whatBrowser) {
        var caratPos = notIE(whatBrowser);
    }
    else if (whatIsIt == "isIE"&& whatBrowser) {
        var caratPos = isIE(whatBrowser);
    };

	// var caratPos = getSelectionStart(el);
	var dotPos = el.value.indexOf(".");
	if (caratPos > dotPos && dotPos>-1 && (number[1].length > 1))
	{
		return false;
	}

	return true;
}

function getSelectionStart(o) {
	if (o.createTextRange) {
		var r = document.selection.createRange().duplicate()
		r.moveEnd('character', o.value.length)
		if (r.text == '') return o.value.length
		return o.value.lastIndexOf(r.text)
	} else return o.selectionStart
}

//Change decimal input to 2 decimal places after typing
function changeDecimalInput(el) {
	var v = parseFloat(el.value);

	if (!isNaN(v))
	{
		el.value = (isNaN(v)) ? '' : v.toFixed(2);
		el.value = parseFloat(el.value);
	}
}

function rtrim(str){
	return str.replace(/\s+$/, "");
}

function ltrim(str){
	return str.replace(/^\s+/, "");
}

function getIt() {
    if (window.getSelection) {
        whatIsIt = "isIE";
        return window.getSelection();
    }
    else if (document.getSelection) {
        whatIsIt = "isIE";
        return document.getSelection();
    }
    else {
        var selection = document.selection && document.selection.createRange();
        if (selection.text) {
            whatIsIt = "notIE";
            return selection;
        };
        return false;
    };
    return false;
};

function isIE(selection) {
    if (selection) {
        var selectionContents = selection.text;
        if (selectionContents) {
            selection.pasteHTML('<span class="reddy">' + selectionContents + '</span>');
        };
    };
};

function notIE(selection) {
    var range = window.getSelection().getRangeAt(0);
    var selectionContents = range.extractContents();
    var span = document.createElement("span");
    span.className= "reddy";
    span.appendChild(selectionContents);
    range.insertNode(span);
};

function sortByKeyDesc(array, key) {
	return array.sort(function (a, b) {
		var x = a[key]; var y = b[key];
		return ((x > y) ? -1 : ((x < y) ? 1 : 0));
	});
}

function sortByKeyAsc(array, key) {
	return array.sort(function (a, b) {
		var x = a[key]; var y = b[key];
		return ((x < y) ? -1 : ((x > y) ? 1 : 0));
	});
}

function getUniqueArrayByKey(array, key)
{
	var arrayLength = array.length;
	var currentValue = [];
	var newArray = [];

	for (var i = 0; i < arrayLength; i++)
	{
		if (currentValue.indexOf(array[i][key]) == -1)
		{
			currentValue.push(array[i][key]);
			newArray.push(array[i]);
		}
	}

	return newArray;
}

function getNumberValueOfArray(array, key, value) //CR-TVJ-000157 Afuuk 19 oct 2021 Add Ancillary on DCS
{
	var arrayLength = array.length;
	var number = 0;

	for (var i = 0; i < arrayLength; i++)
	{
		if (array[i][key] == value)
		{
			number++;
		}
	}

	return number;
}

function message_pop_up_window(message)
{
	console.log(message);
  var display = '\
  <a class="popup_message" id="popup_message" data-toggle="modal" data-target="#message" href="#"></a>\
  <div class="modal fade" id="message" role="dialog">\
        <div class="modal-dialog modal-md">\
          <div class="modal-content">\
            <div class="modal-header">\
              <button type="button" class="close" data-dismiss="modal">&times;</button>\
              <h4 class="modal-title">Messsage </h4>\
            </div>\
            <div class="modal-body">\
              <p>'+message+'</p>\
            </div>\
          </div>\
        </div>\
      </div>\
    </div>';
  return display;
}