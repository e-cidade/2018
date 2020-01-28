<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: orcamento
//CLASSE DA ENTIDADE receita
class cl_receita { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $o08_anousu = 0; 
   var $o08_codrec = null; 
   var $o08_reduz = 0; 
   var $o08_digito = null; 
   var $o08_valor = 0; 
   var $o08_arre01 = 0; 
   var $o08_arre02 = 0; 
   var $o08_arre03 = 0; 
   var $o08_arre04 = 0; 
   var $o08_arre05 = 0; 
   var $o08_arre06 = 0; 
   var $o08_arre07 = 0; 
   var $o08_arre08 = 0; 
   var $o08_arre09 = 0; 
   var $o08_arre10 = 0; 
   var $o08_arre11 = 0; 
   var $o08_arre12 = 0; 
   var $o08_origin = 0; 
   var $o08_lancad = 'f'; 
   var $o08_recurs = null; 
   var $o08_codest = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o08_anousu = int4 = Ano do Exercicio 
                 o08_codrec = char(    10) = Codigo da Receita 
                 o08_reduz = int4 = Codigo Reduzido da Receita 
                 o08_digito = char(     1) = Digito de Controle 
                 o08_valor = float8 = Valor da Receita Prevista 
                 o08_arre01 = float8 = Valor Arrecadado em Janeiro 
                 o08_arre02 = float8 = Valor Arrecadado em Fevereiro 
                 o08_arre03 = float8 = Valor Arrecadado em Marco 
                 o08_arre04 = float8 = Valor Arrecadado em Abril 
                 o08_arre05 = float8 = Valor Arrecadado em Maio 
                 o08_arre06 = float8 = Valor Arrecadado em Junho 
                 o08_arre07 = float8 = Valor Arrecadado em Julho 
                 o08_arre08 = float8 = Valor Arrecadado em Agosto 
                 o08_arre09 = float8 = Valor Arrecadado em Setembro 
                 o08_arre10 = float8 = Valor Arrecadado em Outubro 
                 o08_arre11 = float8 = Valor Arrecadado em Novembro 
                 o08_arre12 = float8 = Valor Arrecadado em Dezembro 
                 o08_origin = float8 = Valor Original da Prev Receita 
                 o08_lancad = boolean = true: receita lancada 
                 o08_recurs = char(     4) = Codigo do Tipo de Recurso 
                 o08_codest = char(    12) = Codigo da receita (novo) 
                 ";
   //funcao construtor da classe 
   function cl_receita() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("receita"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->o08_anousu = ($this->o08_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_anousu"]:$this->o08_anousu);
       $this->o08_codrec = ($this->o08_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_codrec"]:$this->o08_codrec);
       $this->o08_reduz = ($this->o08_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_reduz"]:$this->o08_reduz);
       $this->o08_digito = ($this->o08_digito == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_digito"]:$this->o08_digito);
       $this->o08_valor = ($this->o08_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_valor"]:$this->o08_valor);
       $this->o08_arre01 = ($this->o08_arre01 == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_arre01"]:$this->o08_arre01);
       $this->o08_arre02 = ($this->o08_arre02 == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_arre02"]:$this->o08_arre02);
       $this->o08_arre03 = ($this->o08_arre03 == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_arre03"]:$this->o08_arre03);
       $this->o08_arre04 = ($this->o08_arre04 == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_arre04"]:$this->o08_arre04);
       $this->o08_arre05 = ($this->o08_arre05 == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_arre05"]:$this->o08_arre05);
       $this->o08_arre06 = ($this->o08_arre06 == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_arre06"]:$this->o08_arre06);
       $this->o08_arre07 = ($this->o08_arre07 == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_arre07"]:$this->o08_arre07);
       $this->o08_arre08 = ($this->o08_arre08 == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_arre08"]:$this->o08_arre08);
       $this->o08_arre09 = ($this->o08_arre09 == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_arre09"]:$this->o08_arre09);
       $this->o08_arre10 = ($this->o08_arre10 == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_arre10"]:$this->o08_arre10);
       $this->o08_arre11 = ($this->o08_arre11 == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_arre11"]:$this->o08_arre11);
       $this->o08_arre12 = ($this->o08_arre12 == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_arre12"]:$this->o08_arre12);
       $this->o08_origin = ($this->o08_origin == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_origin"]:$this->o08_origin);
       $this->o08_lancad = ($this->o08_lancad == "f"?@$GLOBALS["HTTP_POST_VARS"]["o08_lancad"]:$this->o08_lancad);
       $this->o08_recurs = ($this->o08_recurs == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_recurs"]:$this->o08_recurs);
       $this->o08_codest = ($this->o08_codest == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_codest"]:$this->o08_codest);
     }else{
       $this->o08_anousu = ($this->o08_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_anousu"]:$this->o08_anousu);
       $this->o08_codest = ($this->o08_codest == ""?@$GLOBALS["HTTP_POST_VARS"]["o08_codest"]:$this->o08_codest);
     }
   }
   // funcao para inclusao
   function incluir ($o08_anousu,$o08_codest){ 
      $this->atualizacampos();
     if($this->o08_codrec == null ){ 
       $this->erro_sql = " Campo Codigo da Receita nao Informado.";
       $this->erro_campo = "o08_codrec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_reduz == null ){ 
       $this->erro_sql = " Campo Codigo Reduzido da Receita nao Informado.";
       $this->erro_campo = "o08_reduz";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_digito == null ){ 
       $this->erro_sql = " Campo Digito de Controle nao Informado.";
       $this->erro_campo = "o08_digito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_valor == null ){ 
       $this->erro_sql = " Campo Valor da Receita Prevista nao Informado.";
       $this->erro_campo = "o08_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_arre01 == null ){ 
       $this->erro_sql = " Campo Valor Arrecadado em Janeiro nao Informado.";
       $this->erro_campo = "o08_arre01";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_arre02 == null ){ 
       $this->erro_sql = " Campo Valor Arrecadado em Fevereiro nao Informado.";
       $this->erro_campo = "o08_arre02";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_arre03 == null ){ 
       $this->erro_sql = " Campo Valor Arrecadado em Marco nao Informado.";
       $this->erro_campo = "o08_arre03";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_arre04 == null ){ 
       $this->erro_sql = " Campo Valor Arrecadado em Abril nao Informado.";
       $this->erro_campo = "o08_arre04";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_arre05 == null ){ 
       $this->erro_sql = " Campo Valor Arrecadado em Maio nao Informado.";
       $this->erro_campo = "o08_arre05";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_arre06 == null ){ 
       $this->erro_sql = " Campo Valor Arrecadado em Junho nao Informado.";
       $this->erro_campo = "o08_arre06";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_arre07 == null ){ 
       $this->erro_sql = " Campo Valor Arrecadado em Julho nao Informado.";
       $this->erro_campo = "o08_arre07";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_arre08 == null ){ 
       $this->erro_sql = " Campo Valor Arrecadado em Agosto nao Informado.";
       $this->erro_campo = "o08_arre08";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_arre09 == null ){ 
       $this->erro_sql = " Campo Valor Arrecadado em Setembro nao Informado.";
       $this->erro_campo = "o08_arre09";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_arre10 == null ){ 
       $this->erro_sql = " Campo Valor Arrecadado em Outubro nao Informado.";
       $this->erro_campo = "o08_arre10";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_arre11 == null ){ 
       $this->erro_sql = " Campo Valor Arrecadado em Novembro nao Informado.";
       $this->erro_campo = "o08_arre11";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_arre12 == null ){ 
       $this->erro_sql = " Campo Valor Arrecadado em Dezembro nao Informado.";
       $this->erro_campo = "o08_arre12";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_origin == null ){ 
       $this->erro_sql = " Campo Valor Original da Prev Receita nao Informado.";
       $this->erro_campo = "o08_origin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_lancad == null ){ 
       $this->erro_sql = " Campo true: receita lancada nao Informado.";
       $this->erro_campo = "o08_lancad";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o08_recurs == null ){ 
       $this->erro_sql = " Campo Codigo do Tipo de Recurso nao Informado.";
       $this->erro_campo = "o08_recurs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o08_anousu = $o08_anousu; 
       $this->o08_codest = $o08_codest; 
     if(($this->o08_anousu == null) || ($this->o08_anousu == "") ){ 
       $this->erro_sql = " Campo o08_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o08_codest == null) || ($this->o08_codest == "") ){ 
       $this->erro_sql = " Campo o08_codest nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into receita(
                                       o08_anousu 
                                      ,o08_codrec 
                                      ,o08_reduz 
                                      ,o08_digito 
                                      ,o08_valor 
                                      ,o08_arre01 
                                      ,o08_arre02 
                                      ,o08_arre03 
                                      ,o08_arre04 
                                      ,o08_arre05 
                                      ,o08_arre06 
                                      ,o08_arre07 
                                      ,o08_arre08 
                                      ,o08_arre09 
                                      ,o08_arre10 
                                      ,o08_arre11 
                                      ,o08_arre12 
                                      ,o08_origin 
                                      ,o08_lancad 
                                      ,o08_recurs 
                                      ,o08_codest 
                       )
                values (
                                $this->o08_anousu 
                               ,'$this->o08_codrec' 
                               ,$this->o08_reduz 
                               ,'$this->o08_digito' 
                               ,$this->o08_valor 
                               ,$this->o08_arre01 
                               ,$this->o08_arre02 
                               ,$this->o08_arre03 
                               ,$this->o08_arre04 
                               ,$this->o08_arre05 
                               ,$this->o08_arre06 
                               ,$this->o08_arre07 
                               ,$this->o08_arre08 
                               ,$this->o08_arre09 
                               ,$this->o08_arre10 
                               ,$this->o08_arre11 
                               ,$this->o08_arre12 
                               ,$this->o08_origin 
                               ,'$this->o08_lancad' 
                               ,'$this->o08_recurs' 
                               ,'$this->o08_codest' 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contem o valor da receita prevista e arrecadada    ($this->o08_anousu."-".$this->o08_codest) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contem o valor da receita prevista e arrecadada    já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contem o valor da receita prevista e arrecadada    ($this->o08_anousu."-".$this->o08_codest) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o08_anousu."-".$this->o08_codest;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->o08_anousu,$this->o08_codest));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,3231,'$this->o08_anousu','I')");
       $resac = pg_query("insert into db_acountkey values($acount,3252,'$this->o08_codest','I')");
       $resac = pg_query("insert into db_acount values($acount,483,3231,'','".pg_result($resaco,0,'o08_anousu')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3232,'','".pg_result($resaco,0,'o08_codrec')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3233,'','".pg_result($resaco,0,'o08_reduz')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3234,'','".pg_result($resaco,0,'o08_digito')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3235,'','".pg_result($resaco,0,'o08_valor')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3236,'','".pg_result($resaco,0,'o08_arre01')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3237,'','".pg_result($resaco,0,'o08_arre02')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3238,'','".pg_result($resaco,0,'o08_arre03')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3239,'','".pg_result($resaco,0,'o08_arre04')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3240,'','".pg_result($resaco,0,'o08_arre05')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3241,'','".pg_result($resaco,0,'o08_arre06')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3242,'','".pg_result($resaco,0,'o08_arre07')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3243,'','".pg_result($resaco,0,'o08_arre08')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3244,'','".pg_result($resaco,0,'o08_arre09')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3245,'','".pg_result($resaco,0,'o08_arre10')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3246,'','".pg_result($resaco,0,'o08_arre11')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3247,'','".pg_result($resaco,0,'o08_arre12')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3249,'','".pg_result($resaco,0,'o08_origin')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3250,'','".pg_result($resaco,0,'o08_lancad')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3251,'','".pg_result($resaco,0,'o08_recurs')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3252,'','".pg_result($resaco,0,'o08_codest')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o08_anousu=null,$o08_codest=null) { 
      $this->atualizacampos();
     $sql = " update receita set ";
     $virgula = "";
     if(trim($this->o08_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_anousu"])){ 
        if(trim($this->o08_anousu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_anousu"])){ 
           $this->o08_anousu = "0" ; 
        } 
       $sql  .= $virgula." o08_anousu = $this->o08_anousu ";
       $virgula = ",";
       if(trim($this->o08_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "o08_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_codrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_codrec"])){ 
       $sql  .= $virgula." o08_codrec = '$this->o08_codrec' ";
       $virgula = ",";
       if(trim($this->o08_codrec) == null ){ 
         $this->erro_sql = " Campo Codigo da Receita nao Informado.";
         $this->erro_campo = "o08_codrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_reduz"])){ 
        if(trim($this->o08_reduz)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_reduz"])){ 
           $this->o08_reduz = "0" ; 
        } 
       $sql  .= $virgula." o08_reduz = $this->o08_reduz ";
       $virgula = ",";
       if(trim($this->o08_reduz) == null ){ 
         $this->erro_sql = " Campo Codigo Reduzido da Receita nao Informado.";
         $this->erro_campo = "o08_reduz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_digito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_digito"])){ 
       $sql  .= $virgula." o08_digito = '$this->o08_digito' ";
       $virgula = ",";
       if(trim($this->o08_digito) == null ){ 
         $this->erro_sql = " Campo Digito de Controle nao Informado.";
         $this->erro_campo = "o08_digito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_valor"])){ 
        if(trim($this->o08_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_valor"])){ 
           $this->o08_valor = "0" ; 
        } 
       $sql  .= $virgula." o08_valor = $this->o08_valor ";
       $virgula = ",";
       if(trim($this->o08_valor) == null ){ 
         $this->erro_sql = " Campo Valor da Receita Prevista nao Informado.";
         $this->erro_campo = "o08_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_arre01)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_arre01"])){ 
        if(trim($this->o08_arre01)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_arre01"])){ 
           $this->o08_arre01 = "0" ; 
        } 
       $sql  .= $virgula." o08_arre01 = $this->o08_arre01 ";
       $virgula = ",";
       if(trim($this->o08_arre01) == null ){ 
         $this->erro_sql = " Campo Valor Arrecadado em Janeiro nao Informado.";
         $this->erro_campo = "o08_arre01";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_arre02)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_arre02"])){ 
        if(trim($this->o08_arre02)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_arre02"])){ 
           $this->o08_arre02 = "0" ; 
        } 
       $sql  .= $virgula." o08_arre02 = $this->o08_arre02 ";
       $virgula = ",";
       if(trim($this->o08_arre02) == null ){ 
         $this->erro_sql = " Campo Valor Arrecadado em Fevereiro nao Informado.";
         $this->erro_campo = "o08_arre02";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_arre03)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_arre03"])){ 
        if(trim($this->o08_arre03)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_arre03"])){ 
           $this->o08_arre03 = "0" ; 
        } 
       $sql  .= $virgula." o08_arre03 = $this->o08_arre03 ";
       $virgula = ",";
       if(trim($this->o08_arre03) == null ){ 
         $this->erro_sql = " Campo Valor Arrecadado em Marco nao Informado.";
         $this->erro_campo = "o08_arre03";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_arre04)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_arre04"])){ 
        if(trim($this->o08_arre04)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_arre04"])){ 
           $this->o08_arre04 = "0" ; 
        } 
       $sql  .= $virgula." o08_arre04 = $this->o08_arre04 ";
       $virgula = ",";
       if(trim($this->o08_arre04) == null ){ 
         $this->erro_sql = " Campo Valor Arrecadado em Abril nao Informado.";
         $this->erro_campo = "o08_arre04";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_arre05)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_arre05"])){ 
        if(trim($this->o08_arre05)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_arre05"])){ 
           $this->o08_arre05 = "0" ; 
        } 
       $sql  .= $virgula." o08_arre05 = $this->o08_arre05 ";
       $virgula = ",";
       if(trim($this->o08_arre05) == null ){ 
         $this->erro_sql = " Campo Valor Arrecadado em Maio nao Informado.";
         $this->erro_campo = "o08_arre05";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_arre06)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_arre06"])){ 
        if(trim($this->o08_arre06)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_arre06"])){ 
           $this->o08_arre06 = "0" ; 
        } 
       $sql  .= $virgula." o08_arre06 = $this->o08_arre06 ";
       $virgula = ",";
       if(trim($this->o08_arre06) == null ){ 
         $this->erro_sql = " Campo Valor Arrecadado em Junho nao Informado.";
         $this->erro_campo = "o08_arre06";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_arre07)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_arre07"])){ 
        if(trim($this->o08_arre07)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_arre07"])){ 
           $this->o08_arre07 = "0" ; 
        } 
       $sql  .= $virgula." o08_arre07 = $this->o08_arre07 ";
       $virgula = ",";
       if(trim($this->o08_arre07) == null ){ 
         $this->erro_sql = " Campo Valor Arrecadado em Julho nao Informado.";
         $this->erro_campo = "o08_arre07";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_arre08)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_arre08"])){ 
        if(trim($this->o08_arre08)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_arre08"])){ 
           $this->o08_arre08 = "0" ; 
        } 
       $sql  .= $virgula." o08_arre08 = $this->o08_arre08 ";
       $virgula = ",";
       if(trim($this->o08_arre08) == null ){ 
         $this->erro_sql = " Campo Valor Arrecadado em Agosto nao Informado.";
         $this->erro_campo = "o08_arre08";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_arre09)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_arre09"])){ 
        if(trim($this->o08_arre09)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_arre09"])){ 
           $this->o08_arre09 = "0" ; 
        } 
       $sql  .= $virgula." o08_arre09 = $this->o08_arre09 ";
       $virgula = ",";
       if(trim($this->o08_arre09) == null ){ 
         $this->erro_sql = " Campo Valor Arrecadado em Setembro nao Informado.";
         $this->erro_campo = "o08_arre09";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_arre10)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_arre10"])){ 
        if(trim($this->o08_arre10)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_arre10"])){ 
           $this->o08_arre10 = "0" ; 
        } 
       $sql  .= $virgula." o08_arre10 = $this->o08_arre10 ";
       $virgula = ",";
       if(trim($this->o08_arre10) == null ){ 
         $this->erro_sql = " Campo Valor Arrecadado em Outubro nao Informado.";
         $this->erro_campo = "o08_arre10";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_arre11)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_arre11"])){ 
        if(trim($this->o08_arre11)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_arre11"])){ 
           $this->o08_arre11 = "0" ; 
        } 
       $sql  .= $virgula." o08_arre11 = $this->o08_arre11 ";
       $virgula = ",";
       if(trim($this->o08_arre11) == null ){ 
         $this->erro_sql = " Campo Valor Arrecadado em Novembro nao Informado.";
         $this->erro_campo = "o08_arre11";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_arre12)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_arre12"])){ 
        if(trim($this->o08_arre12)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_arre12"])){ 
           $this->o08_arre12 = "0" ; 
        } 
       $sql  .= $virgula." o08_arre12 = $this->o08_arre12 ";
       $virgula = ",";
       if(trim($this->o08_arre12) == null ){ 
         $this->erro_sql = " Campo Valor Arrecadado em Dezembro nao Informado.";
         $this->erro_campo = "o08_arre12";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_origin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_origin"])){ 
        if(trim($this->o08_origin)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o08_origin"])){ 
           $this->o08_origin = "0" ; 
        } 
       $sql  .= $virgula." o08_origin = $this->o08_origin ";
       $virgula = ",";
       if(trim($this->o08_origin) == null ){ 
         $this->erro_sql = " Campo Valor Original da Prev Receita nao Informado.";
         $this->erro_campo = "o08_origin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_lancad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_lancad"])){ 
       $sql  .= $virgula." o08_lancad = '$this->o08_lancad' ";
       $virgula = ",";
       if(trim($this->o08_lancad) == null ){ 
         $this->erro_sql = " Campo true: receita lancada nao Informado.";
         $this->erro_campo = "o08_lancad";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_recurs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_recurs"])){ 
       $sql  .= $virgula." o08_recurs = '$this->o08_recurs' ";
       $virgula = ",";
       if(trim($this->o08_recurs) == null ){ 
         $this->erro_sql = " Campo Codigo do Tipo de Recurso nao Informado.";
         $this->erro_campo = "o08_recurs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o08_codest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o08_codest"])){ 
       $sql  .= $virgula." o08_codest = '$this->o08_codest' ";
       $virgula = ",";
       if(trim($this->o08_codest) == null ){ 
         $this->erro_sql = " Campo Codigo da receita (novo) nao Informado.";
         $this->erro_campo = "o08_codest";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  o08_anousu = $this->o08_anousu
 and  o08_codest = '$this->o08_codest'
";
     $resaco = $this->sql_record($this->sql_query_file($this->o08_anousu,$this->o08_codest));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,3231,'$this->o08_anousu','A')");
       $resac = pg_query("insert into db_acountkey values($acount,3252,'$this->o08_codest','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_anousu"]))
         $resac = pg_query("insert into db_acount values($acount,483,3231,'".pg_result($resaco,0,'o08_anousu')."','$this->o08_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_codrec"]))
         $resac = pg_query("insert into db_acount values($acount,483,3232,'".pg_result($resaco,0,'o08_codrec')."','$this->o08_codrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_reduz"]))
         $resac = pg_query("insert into db_acount values($acount,483,3233,'".pg_result($resaco,0,'o08_reduz')."','$this->o08_reduz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_digito"]))
         $resac = pg_query("insert into db_acount values($acount,483,3234,'".pg_result($resaco,0,'o08_digito')."','$this->o08_digito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_valor"]))
         $resac = pg_query("insert into db_acount values($acount,483,3235,'".pg_result($resaco,0,'o08_valor')."','$this->o08_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_arre01"]))
         $resac = pg_query("insert into db_acount values($acount,483,3236,'".pg_result($resaco,0,'o08_arre01')."','$this->o08_arre01',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_arre02"]))
         $resac = pg_query("insert into db_acount values($acount,483,3237,'".pg_result($resaco,0,'o08_arre02')."','$this->o08_arre02',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_arre03"]))
         $resac = pg_query("insert into db_acount values($acount,483,3238,'".pg_result($resaco,0,'o08_arre03')."','$this->o08_arre03',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_arre04"]))
         $resac = pg_query("insert into db_acount values($acount,483,3239,'".pg_result($resaco,0,'o08_arre04')."','$this->o08_arre04',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_arre05"]))
         $resac = pg_query("insert into db_acount values($acount,483,3240,'".pg_result($resaco,0,'o08_arre05')."','$this->o08_arre05',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_arre06"]))
         $resac = pg_query("insert into db_acount values($acount,483,3241,'".pg_result($resaco,0,'o08_arre06')."','$this->o08_arre06',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_arre07"]))
         $resac = pg_query("insert into db_acount values($acount,483,3242,'".pg_result($resaco,0,'o08_arre07')."','$this->o08_arre07',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_arre08"]))
         $resac = pg_query("insert into db_acount values($acount,483,3243,'".pg_result($resaco,0,'o08_arre08')."','$this->o08_arre08',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_arre09"]))
         $resac = pg_query("insert into db_acount values($acount,483,3244,'".pg_result($resaco,0,'o08_arre09')."','$this->o08_arre09',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_arre10"]))
         $resac = pg_query("insert into db_acount values($acount,483,3245,'".pg_result($resaco,0,'o08_arre10')."','$this->o08_arre10',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_arre11"]))
         $resac = pg_query("insert into db_acount values($acount,483,3246,'".pg_result($resaco,0,'o08_arre11')."','$this->o08_arre11',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_arre12"]))
         $resac = pg_query("insert into db_acount values($acount,483,3247,'".pg_result($resaco,0,'o08_arre12')."','$this->o08_arre12',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_origin"]))
         $resac = pg_query("insert into db_acount values($acount,483,3249,'".pg_result($resaco,0,'o08_origin')."','$this->o08_origin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_lancad"]))
         $resac = pg_query("insert into db_acount values($acount,483,3250,'".pg_result($resaco,0,'o08_lancad')."','$this->o08_lancad',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_recurs"]))
         $resac = pg_query("insert into db_acount values($acount,483,3251,'".pg_result($resaco,0,'o08_recurs')."','$this->o08_recurs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["o08_codest"]))
         $resac = pg_query("insert into db_acount values($acount,483,3252,'".pg_result($resaco,0,'o08_codest')."','$this->o08_codest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contem o valor da receita prevista e arrecadada    nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o08_anousu."-".$this->o08_codest;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contem o valor da receita prevista e arrecadada    nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o08_anousu."-".$this->o08_codest;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o08_anousu."-".$this->o08_codest;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o08_anousu=null,$o08_codest=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->o08_anousu,$this->o08_codest));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,3231,'".pg_result($resaco,$iresaco,'o08_anousu')."','E')");
       $resac = pg_query("insert into db_acountkey values($acount,3252,'".pg_result($resaco,$iresaco,'o08_codest')."','E')");
       $resac = pg_query("insert into db_acount values($acount,483,3231,'','".pg_result($resaco,0,'o08_anousu')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3232,'','".pg_result($resaco,0,'o08_codrec')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3233,'','".pg_result($resaco,0,'o08_reduz')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3234,'','".pg_result($resaco,0,'o08_digito')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3235,'','".pg_result($resaco,0,'o08_valor')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3236,'','".pg_result($resaco,0,'o08_arre01')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3237,'','".pg_result($resaco,0,'o08_arre02')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3238,'','".pg_result($resaco,0,'o08_arre03')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3239,'','".pg_result($resaco,0,'o08_arre04')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3240,'','".pg_result($resaco,0,'o08_arre05')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3241,'','".pg_result($resaco,0,'o08_arre06')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3242,'','".pg_result($resaco,0,'o08_arre07')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3243,'','".pg_result($resaco,0,'o08_arre08')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3244,'','".pg_result($resaco,0,'o08_arre09')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3245,'','".pg_result($resaco,0,'o08_arre10')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3246,'','".pg_result($resaco,0,'o08_arre11')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3247,'','".pg_result($resaco,0,'o08_arre12')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3249,'','".pg_result($resaco,0,'o08_origin')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3250,'','".pg_result($resaco,0,'o08_lancad')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3251,'','".pg_result($resaco,0,'o08_recurs')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,483,3252,'','".pg_result($resaco,0,'o08_codest')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from receita
                    where ";
     $sql2 = "";
      if($this->o08_anousu != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " o08_anousu = $this->o08_anousu ";
}
      if($this->o08_codest != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " o08_codest = '$this->o08_codest' ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contem o valor da receita prevista e arrecadada    nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->o08_anousu."-".$this->o08_codest;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contem o valor da receita prevista e arrecadada    nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->o08_anousu."-".$this->o08_codest;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão Efetivada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o08_anousu."-".$this->o08_codest;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o08_anousu=null,$o08_codest=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from receita ";
     $sql2 = "";
     if($dbwhere==""){
       if($o08_anousu!=null ){
         $sql2 .= " where receita.o08_anousu = $o08_anousu "; 
       } 
       if($o08_codest!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " receita.o08_codest = '$o08_codest' "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $o08_anousu=null,$o08_codest=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from receita ";
     $sql2 = "";
     if($dbwhere==""){
       if($o08_anousu!=null ){
         $sql2 .= " where receita.o08_anousu = $o08_anousu "; 
       } 
       if($o08_codest!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " receita.o08_codest = '$o08_codest' "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>