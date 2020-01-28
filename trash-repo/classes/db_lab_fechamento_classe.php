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

//MODULO: laboratorio
//CLASSE DA ENTIDADE lab_fechamento
class cl_lab_fechamento { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $la54_i_codigo = 0; 
   var $la54_d_data_dia = null; 
   var $la54_d_data_mes = null; 
   var $la54_d_data_ano = null; 
   var $la54_d_data = null; 
   var $la54_c_hora = null; 
   var $la54_i_compano = 0; 
   var $la54_i_compmes = 0; 
   var $la54_i_login = 0; 
   var $la54_c_descr = null; 
   var $la54_d_ini_dia = null; 
   var $la54_d_ini_mes = null; 
   var $la54_d_ini_ano = null; 
   var $la54_d_ini = null; 
   var $la54_d_fim_dia = null; 
   var $la54_d_fim_mes = null; 
   var $la54_d_fim_ano = null; 
   var $la54_d_fim = null; 
   var $la54_i_financiamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la54_i_codigo = int4 = C�digo 
                 la54_d_data = date = Data 
                 la54_c_hora = char(5) = hora 
                 la54_i_compano = int4 = Ano competencia 
                 la54_i_compmes = int4 = Compt�ncia m�s 
                 la54_i_login = int4 = Usu�rio 
                 la54_c_descr = char(50) = Descri��o 
                 la54_d_ini = date = inicio 
                 la54_d_fim = date = Fim 
                 la54_i_financiamento = int4 = Tipo de Financiamento 
                 ";
   //funcao construtor da classe 
   function cl_lab_fechamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_fechamento"); 
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
       $this->la54_i_codigo = ($this->la54_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_i_codigo"]:$this->la54_i_codigo);
       if($this->la54_d_data == ""){
         $this->la54_d_data_dia = ($this->la54_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_d_data_dia"]:$this->la54_d_data_dia);
         $this->la54_d_data_mes = ($this->la54_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_d_data_mes"]:$this->la54_d_data_mes);
         $this->la54_d_data_ano = ($this->la54_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_d_data_ano"]:$this->la54_d_data_ano);
         if($this->la54_d_data_dia != ""){
            $this->la54_d_data = $this->la54_d_data_ano."-".$this->la54_d_data_mes."-".$this->la54_d_data_dia;
         }
       }
       $this->la54_c_hora = ($this->la54_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_c_hora"]:$this->la54_c_hora);
       $this->la54_i_compano = ($this->la54_i_compano == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_i_compano"]:$this->la54_i_compano);
       $this->la54_i_compmes = ($this->la54_i_compmes == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_i_compmes"]:$this->la54_i_compmes);
       $this->la54_i_login = ($this->la54_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_i_login"]:$this->la54_i_login);
       $this->la54_c_descr = ($this->la54_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_c_descr"]:$this->la54_c_descr);
       if($this->la54_d_ini == ""){
         $this->la54_d_ini_dia = ($this->la54_d_ini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_d_ini_dia"]:$this->la54_d_ini_dia);
         $this->la54_d_ini_mes = ($this->la54_d_ini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_d_ini_mes"]:$this->la54_d_ini_mes);
         $this->la54_d_ini_ano = ($this->la54_d_ini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_d_ini_ano"]:$this->la54_d_ini_ano);
         if($this->la54_d_ini_dia != ""){
            $this->la54_d_ini = $this->la54_d_ini_ano."-".$this->la54_d_ini_mes."-".$this->la54_d_ini_dia;
         }
       }
       if($this->la54_d_fim == ""){
         $this->la54_d_fim_dia = ($this->la54_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_d_fim_dia"]:$this->la54_d_fim_dia);
         $this->la54_d_fim_mes = ($this->la54_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_d_fim_mes"]:$this->la54_d_fim_mes);
         $this->la54_d_fim_ano = ($this->la54_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_d_fim_ano"]:$this->la54_d_fim_ano);
         if($this->la54_d_fim_dia != ""){
            $this->la54_d_fim = $this->la54_d_fim_ano."-".$this->la54_d_fim_mes."-".$this->la54_d_fim_dia;
         }
       }
       $this->la54_i_financiamento = ($this->la54_i_financiamento == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_i_financiamento"]:$this->la54_i_financiamento);
     }else{
       $this->la54_i_codigo = ($this->la54_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la54_i_codigo"]:$this->la54_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la54_i_codigo){ 
      $this->atualizacampos();
     if($this->la54_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "la54_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la54_c_hora == null ){ 
       $this->erro_sql = " Campo hora nao Informado.";
       $this->erro_campo = "la54_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la54_i_compano == null ){ 
       $this->erro_sql = " Campo Ano competencia nao Informado.";
       $this->erro_campo = "la54_i_compano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la54_i_compmes == null ){ 
       $this->erro_sql = " Campo Compt�ncia m�s nao Informado.";
       $this->erro_campo = "la54_i_compmes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la54_i_login == null ){ 
       $this->erro_sql = " Campo Usu�rio nao Informado.";
       $this->erro_campo = "la54_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la54_c_descr == null ){ 
       $this->erro_sql = " Campo Descri��o nao Informado.";
       $this->erro_campo = "la54_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la54_d_ini == null ){ 
       $this->erro_sql = " Campo inicio nao Informado.";
       $this->erro_campo = "la54_d_ini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la54_d_fim == null ){ 
       $this->erro_sql = " Campo Fim nao Informado.";
       $this->erro_campo = "la54_d_fim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la54_i_financiamento == null ){ 
       $this->la54_i_financiamento = "0";
     }
     if($la54_i_codigo == "" || $la54_i_codigo == null ){
       $result = db_query("select nextval('lab_fechamento_la54_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_fechamento_la54_i_codigo_seq do campo: la54_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la54_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_fechamento_la54_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la54_i_codigo)){
         $this->erro_sql = " Campo la54_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la54_i_codigo = $la54_i_codigo; 
       }
     }
     if(($this->la54_i_codigo == null) || ($this->la54_i_codigo == "") ){ 
       $this->erro_sql = " Campo la54_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_fechamento(
                                       la54_i_codigo 
                                      ,la54_d_data 
                                      ,la54_c_hora 
                                      ,la54_i_compano 
                                      ,la54_i_compmes 
                                      ,la54_i_login 
                                      ,la54_c_descr 
                                      ,la54_d_ini 
                                      ,la54_d_fim 
                                      ,la54_i_financiamento 
                       )
                values (
                                $this->la54_i_codigo 
                               ,".($this->la54_d_data == "null" || $this->la54_d_data == ""?"null":"'".$this->la54_d_data."'")." 
                               ,'$this->la54_c_hora' 
                               ,$this->la54_i_compano 
                               ,$this->la54_i_compmes 
                               ,$this->la54_i_login 
                               ,'$this->la54_c_descr' 
                               ,".($this->la54_d_ini == "null" || $this->la54_d_ini == ""?"null":"'".$this->la54_d_ini."'")." 
                               ,".($this->la54_d_fim == "null" || $this->la54_d_fim == ""?"null":"'".$this->la54_d_fim."'")." 
                               ,$this->la54_i_financiamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fechamento do arquivo BPA ($this->la54_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fechamento do arquivo BPA j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fechamento do arquivo BPA ($this->la54_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la54_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la54_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16738,'$this->la54_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2948,16738,'','".AddSlashes(pg_result($resaco,0,'la54_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2948,16739,'','".AddSlashes(pg_result($resaco,0,'la54_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2948,16740,'','".AddSlashes(pg_result($resaco,0,'la54_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2948,16741,'','".AddSlashes(pg_result($resaco,0,'la54_i_compano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2948,16742,'','".AddSlashes(pg_result($resaco,0,'la54_i_compmes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2948,16743,'','".AddSlashes(pg_result($resaco,0,'la54_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2948,16744,'','".AddSlashes(pg_result($resaco,0,'la54_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2948,16745,'','".AddSlashes(pg_result($resaco,0,'la54_d_ini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2948,16746,'','".AddSlashes(pg_result($resaco,0,'la54_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2948,17783,'','".AddSlashes(pg_result($resaco,0,'la54_i_financiamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la54_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_fechamento set ";
     $virgula = "";
     if(trim($this->la54_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la54_i_codigo"])){ 
       $sql  .= $virgula." la54_i_codigo = $this->la54_i_codigo ";
       $virgula = ",";
       if(trim($this->la54_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "la54_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la54_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la54_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la54_d_data_dia"] !="") ){ 
       $sql  .= $virgula." la54_d_data = '$this->la54_d_data' ";
       $virgula = ",";
       if(trim($this->la54_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "la54_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la54_d_data_dia"])){ 
         $sql  .= $virgula." la54_d_data = null ";
         $virgula = ",";
         if(trim($this->la54_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "la54_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la54_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la54_c_hora"])){ 
       $sql  .= $virgula." la54_c_hora = '$this->la54_c_hora' ";
       $virgula = ",";
       if(trim($this->la54_c_hora) == null ){ 
         $this->erro_sql = " Campo hora nao Informado.";
         $this->erro_campo = "la54_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la54_i_compano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la54_i_compano"])){ 
       $sql  .= $virgula." la54_i_compano = $this->la54_i_compano ";
       $virgula = ",";
       if(trim($this->la54_i_compano) == null ){ 
         $this->erro_sql = " Campo Ano competencia nao Informado.";
         $this->erro_campo = "la54_i_compano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la54_i_compmes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la54_i_compmes"])){ 
       $sql  .= $virgula." la54_i_compmes = $this->la54_i_compmes ";
       $virgula = ",";
       if(trim($this->la54_i_compmes) == null ){ 
         $this->erro_sql = " Campo Compt�ncia m�s nao Informado.";
         $this->erro_campo = "la54_i_compmes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la54_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la54_i_login"])){ 
       $sql  .= $virgula." la54_i_login = $this->la54_i_login ";
       $virgula = ",";
       if(trim($this->la54_i_login) == null ){ 
         $this->erro_sql = " Campo Usu�rio nao Informado.";
         $this->erro_campo = "la54_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la54_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la54_c_descr"])){ 
       $sql  .= $virgula." la54_c_descr = '$this->la54_c_descr' ";
       $virgula = ",";
       if(trim($this->la54_c_descr) == null ){ 
         $this->erro_sql = " Campo Descri��o nao Informado.";
         $this->erro_campo = "la54_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la54_d_ini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la54_d_ini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la54_d_ini_dia"] !="") ){ 
       $sql  .= $virgula." la54_d_ini = '$this->la54_d_ini' ";
       $virgula = ",";
       if(trim($this->la54_d_ini) == null ){ 
         $this->erro_sql = " Campo inicio nao Informado.";
         $this->erro_campo = "la54_d_ini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la54_d_ini_dia"])){ 
         $sql  .= $virgula." la54_d_ini = null ";
         $virgula = ",";
         if(trim($this->la54_d_ini) == null ){ 
           $this->erro_sql = " Campo inicio nao Informado.";
           $this->erro_campo = "la54_d_ini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la54_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la54_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la54_d_fim_dia"] !="") ){ 
       $sql  .= $virgula." la54_d_fim = '$this->la54_d_fim' ";
       $virgula = ",";
       if(trim($this->la54_d_fim) == null ){ 
         $this->erro_sql = " Campo Fim nao Informado.";
         $this->erro_campo = "la54_d_fim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la54_d_fim_dia"])){ 
         $sql  .= $virgula." la54_d_fim = null ";
         $virgula = ",";
         if(trim($this->la54_d_fim) == null ){ 
           $this->erro_sql = " Campo Fim nao Informado.";
           $this->erro_campo = "la54_d_fim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la54_i_financiamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la54_i_financiamento"])){ 
        if(trim($this->la54_i_financiamento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la54_i_financiamento"])){ 
           $this->la54_i_financiamento = "0" ; 
        } 
       $sql  .= $virgula." la54_i_financiamento = $this->la54_i_financiamento ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($la54_i_codigo!=null){
       $sql .= " la54_i_codigo = $this->la54_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la54_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16738,'$this->la54_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la54_i_codigo"]) || $this->la54_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2948,16738,'".AddSlashes(pg_result($resaco,$conresaco,'la54_i_codigo'))."','$this->la54_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la54_d_data"]) || $this->la54_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2948,16739,'".AddSlashes(pg_result($resaco,$conresaco,'la54_d_data'))."','$this->la54_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la54_c_hora"]) || $this->la54_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2948,16740,'".AddSlashes(pg_result($resaco,$conresaco,'la54_c_hora'))."','$this->la54_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la54_i_compano"]) || $this->la54_i_compano != "")
           $resac = db_query("insert into db_acount values($acount,2948,16741,'".AddSlashes(pg_result($resaco,$conresaco,'la54_i_compano'))."','$this->la54_i_compano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la54_i_compmes"]) || $this->la54_i_compmes != "")
           $resac = db_query("insert into db_acount values($acount,2948,16742,'".AddSlashes(pg_result($resaco,$conresaco,'la54_i_compmes'))."','$this->la54_i_compmes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la54_i_login"]) || $this->la54_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2948,16743,'".AddSlashes(pg_result($resaco,$conresaco,'la54_i_login'))."','$this->la54_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la54_c_descr"]) || $this->la54_c_descr != "")
           $resac = db_query("insert into db_acount values($acount,2948,16744,'".AddSlashes(pg_result($resaco,$conresaco,'la54_c_descr'))."','$this->la54_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la54_d_ini"]) || $this->la54_d_ini != "")
           $resac = db_query("insert into db_acount values($acount,2948,16745,'".AddSlashes(pg_result($resaco,$conresaco,'la54_d_ini'))."','$this->la54_d_ini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la54_d_fim"]) || $this->la54_d_fim != "")
           $resac = db_query("insert into db_acount values($acount,2948,16746,'".AddSlashes(pg_result($resaco,$conresaco,'la54_d_fim'))."','$this->la54_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la54_i_financiamento"]) || $this->la54_i_financiamento != "")
           $resac = db_query("insert into db_acount values($acount,2948,17783,'".AddSlashes(pg_result($resaco,$conresaco,'la54_i_financiamento'))."','$this->la54_i_financiamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fechamento do arquivo BPA nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la54_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fechamento do arquivo BPA nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la54_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la54_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la54_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la54_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16738,'$la54_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2948,16738,'','".AddSlashes(pg_result($resaco,$iresaco,'la54_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2948,16739,'','".AddSlashes(pg_result($resaco,$iresaco,'la54_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2948,16740,'','".AddSlashes(pg_result($resaco,$iresaco,'la54_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2948,16741,'','".AddSlashes(pg_result($resaco,$iresaco,'la54_i_compano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2948,16742,'','".AddSlashes(pg_result($resaco,$iresaco,'la54_i_compmes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2948,16743,'','".AddSlashes(pg_result($resaco,$iresaco,'la54_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2948,16744,'','".AddSlashes(pg_result($resaco,$iresaco,'la54_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2948,16745,'','".AddSlashes(pg_result($resaco,$iresaco,'la54_d_ini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2948,16746,'','".AddSlashes(pg_result($resaco,$iresaco,'la54_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2948,17783,'','".AddSlashes(pg_result($resaco,$iresaco,'la54_i_financiamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_fechamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la54_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la54_i_codigo = $la54_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fechamento do arquivo BPA nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la54_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fechamento do arquivo BPA nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la54_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la54_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:lab_fechamento";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la54_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_fechamento ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lab_fechamento.la54_i_login";
     $sql .= "      left  join sau_financiamento  on  sau_financiamento.sd65_i_codigo = lab_fechamento.la54_i_financiamento";
     $sql2 = "";
     if($dbwhere==""){
       if($la54_i_codigo!=null ){
         $sql2 .= " where lab_fechamento.la54_i_codigo = $la54_i_codigo "; 
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
   function sql_query_file ( $la54_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_fechamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($la54_i_codigo!=null ){
         $sql2 .= " where lab_fechamento.la54_i_codigo = $la54_i_codigo "; 
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