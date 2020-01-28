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
//CLASSE DA ENTIDADE lab_horario
class cl_lab_horario { 
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
   var $la35_i_codigo = 0; 
   var $la35_i_setorexame = 0; 
   var $la35_i_diasemana = 0; 
   var $la35_c_horaini = null; 
   var $la35_c_horafim = null; 
   var $la35_d_valinicio_dia = null; 
   var $la35_d_valinicio_mes = null; 
   var $la35_d_valinicio_ano = null; 
   var $la35_d_valinicio = null; 
   var $la35_d_valfim_dia = null; 
   var $la35_d_valfim_mes = null; 
   var $la35_d_valfim_ano = null; 
   var $la35_d_valfim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la35_i_codigo = int4 = C�digo 
                 la35_i_setorexame = int4 = Setor Exame 
                 la35_i_diasemana = int4 = Dia Semana 
                 la35_c_horaini = char(5) = Hora Inicial 
                 la35_c_horafim = char(5) = Hora final 
                 la35_d_valinicio = date = Validade Inicial 
                 la35_d_valfim = date = Validade Final 
                 ";
   //funcao construtor da classe 
   function cl_lab_horario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_horario"); 
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
       $this->la35_i_codigo = ($this->la35_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la35_i_codigo"]:$this->la35_i_codigo);
       $this->la35_i_setorexame = ($this->la35_i_setorexame == ""?@$GLOBALS["HTTP_POST_VARS"]["la35_i_setorexame"]:$this->la35_i_setorexame);
       $this->la35_i_diasemana = ($this->la35_i_diasemana == ""?@$GLOBALS["HTTP_POST_VARS"]["la35_i_diasemana"]:$this->la35_i_diasemana);
       $this->la35_c_horaini = ($this->la35_c_horaini == ""?@$GLOBALS["HTTP_POST_VARS"]["la35_c_horaini"]:$this->la35_c_horaini);
       $this->la35_c_horafim = ($this->la35_c_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["la35_c_horafim"]:$this->la35_c_horafim);
       if($this->la35_d_valinicio == ""){
         $this->la35_d_valinicio_dia = ($this->la35_d_valinicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la35_d_valinicio_dia"]:$this->la35_d_valinicio_dia);
         $this->la35_d_valinicio_mes = ($this->la35_d_valinicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la35_d_valinicio_mes"]:$this->la35_d_valinicio_mes);
         $this->la35_d_valinicio_ano = ($this->la35_d_valinicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la35_d_valinicio_ano"]:$this->la35_d_valinicio_ano);
         if($this->la35_d_valinicio_dia != ""){
            $this->la35_d_valinicio = $this->la35_d_valinicio_ano."-".$this->la35_d_valinicio_mes."-".$this->la35_d_valinicio_dia;
         }
       }
       if($this->la35_d_valfim == ""){
         $this->la35_d_valfim_dia = ($this->la35_d_valfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la35_d_valfim_dia"]:$this->la35_d_valfim_dia);
         $this->la35_d_valfim_mes = ($this->la35_d_valfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la35_d_valfim_mes"]:$this->la35_d_valfim_mes);
         $this->la35_d_valfim_ano = ($this->la35_d_valfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la35_d_valfim_ano"]:$this->la35_d_valfim_ano);
         if($this->la35_d_valfim_dia != ""){
            $this->la35_d_valfim = $this->la35_d_valfim_ano."-".$this->la35_d_valfim_mes."-".$this->la35_d_valfim_dia;
         }
       }
     }else{
       $this->la35_i_codigo = ($this->la35_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la35_i_codigo"]:$this->la35_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la35_i_codigo){ 
      $this->atualizacampos();
     if($this->la35_i_setorexame == null ){ 
       $this->erro_sql = " Campo Setor Exame nao Informado.";
       $this->erro_campo = "la35_i_setorexame";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la35_i_diasemana == null ){ 
       $this->erro_sql = " Campo Dia Semana nao Informado.";
       $this->erro_campo = "la35_i_diasemana";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la35_c_horaini == null ){ 
       $this->erro_sql = " Campo Hora Inicial nao Informado.";
       $this->erro_campo = "la35_c_horaini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la35_c_horafim == null ){ 
       $this->erro_sql = " Campo Hora final nao Informado.";
       $this->erro_campo = "la35_c_horafim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la35_d_valinicio == null ){ 
       $this->la35_d_valinicio = "null";
     }
     if($this->la35_d_valfim == null ){ 
       $this->la35_d_valfim = "null";
     }
     if($la35_i_codigo == "" || $la35_i_codigo == null ){
       $result = db_query("select nextval('lab_horario_la35_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_horario_la35_i_codigo_seq do campo: la35_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la35_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_horario_la35_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la35_i_codigo)){
         $this->erro_sql = " Campo la35_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la35_i_codigo = $la35_i_codigo; 
       }
     }
     if(($this->la35_i_codigo == null) || ($this->la35_i_codigo == "") ){ 
       $this->erro_sql = " Campo la35_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_horario(
                                       la35_i_codigo 
                                      ,la35_i_setorexame 
                                      ,la35_i_diasemana 
                                      ,la35_c_horaini 
                                      ,la35_c_horafim 
                                      ,la35_d_valinicio 
                                      ,la35_d_valfim 
                       )
                values (
                                $this->la35_i_codigo 
                               ,$this->la35_i_setorexame 
                               ,$this->la35_i_diasemana 
                               ,'$this->la35_c_horaini' 
                               ,'$this->la35_c_horafim' 
                               ,".($this->la35_d_valinicio == "null" || $this->la35_d_valinicio == ""?"null":"'".$this->la35_d_valinicio."'")." 
                               ,".($this->la35_d_valfim == "null" || $this->la35_d_valfim == ""?"null":"'".$this->la35_d_valfim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lab_horario ($this->la35_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lab_horario j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lab_horario ($this->la35_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la35_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la35_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15811,'$this->la35_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2776,15811,'','".AddSlashes(pg_result($resaco,0,'la35_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2776,15812,'','".AddSlashes(pg_result($resaco,0,'la35_i_setorexame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2776,15814,'','".AddSlashes(pg_result($resaco,0,'la35_i_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2776,15816,'','".AddSlashes(pg_result($resaco,0,'la35_c_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2776,15817,'','".AddSlashes(pg_result($resaco,0,'la35_c_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2776,15818,'','".AddSlashes(pg_result($resaco,0,'la35_d_valinicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2776,15819,'','".AddSlashes(pg_result($resaco,0,'la35_d_valfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la35_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_horario set ";
     $virgula = "";
     if(trim($this->la35_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la35_i_codigo"])){ 
       $sql  .= $virgula." la35_i_codigo = $this->la35_i_codigo ";
       $virgula = ",";
       if(trim($this->la35_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "la35_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la35_i_setorexame)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la35_i_setorexame"])){ 
       $sql  .= $virgula." la35_i_setorexame = $this->la35_i_setorexame ";
       $virgula = ",";
       if(trim($this->la35_i_setorexame) == null ){ 
         $this->erro_sql = " Campo Setor Exame nao Informado.";
         $this->erro_campo = "la35_i_setorexame";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la35_i_diasemana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la35_i_diasemana"])){ 
       $sql  .= $virgula." la35_i_diasemana = $this->la35_i_diasemana ";
       $virgula = ",";
       if(trim($this->la35_i_diasemana) == null ){ 
         $this->erro_sql = " Campo Dia Semana nao Informado.";
         $this->erro_campo = "la35_i_diasemana";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la35_c_horaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la35_c_horaini"])){ 
       $sql  .= $virgula." la35_c_horaini = '$this->la35_c_horaini' ";
       $virgula = ",";
       if(trim($this->la35_c_horaini) == null ){ 
         $this->erro_sql = " Campo Hora Inicial nao Informado.";
         $this->erro_campo = "la35_c_horaini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la35_c_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la35_c_horafim"])){ 
       $sql  .= $virgula." la35_c_horafim = '$this->la35_c_horafim' ";
       $virgula = ",";
       if(trim($this->la35_c_horafim) == null ){ 
         $this->erro_sql = " Campo Hora final nao Informado.";
         $this->erro_campo = "la35_c_horafim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la35_d_valinicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la35_d_valinicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la35_d_valinicio_dia"] !="") ){ 
       $sql  .= $virgula." la35_d_valinicio = '$this->la35_d_valinicio' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la35_d_valinicio_dia"])){ 
         $sql  .= $virgula." la35_d_valinicio = null ";
         $virgula = ",";
       }
     }
     if(trim($this->la35_d_valfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la35_d_valfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la35_d_valfim_dia"] !="") ){ 
       $sql  .= $virgula." la35_d_valfim = '$this->la35_d_valfim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la35_d_valfim_dia"])){ 
         $sql  .= $virgula." la35_d_valfim = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($la35_i_codigo!=null){
       $sql .= " la35_i_codigo = $this->la35_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la35_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15811,'$this->la35_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la35_i_codigo"]) || $this->la35_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2776,15811,'".AddSlashes(pg_result($resaco,$conresaco,'la35_i_codigo'))."','$this->la35_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la35_i_setorexame"]) || $this->la35_i_setorexame != "")
           $resac = db_query("insert into db_acount values($acount,2776,15812,'".AddSlashes(pg_result($resaco,$conresaco,'la35_i_setorexame'))."','$this->la35_i_setorexame',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la35_i_diasemana"]) || $this->la35_i_diasemana != "")
           $resac = db_query("insert into db_acount values($acount,2776,15814,'".AddSlashes(pg_result($resaco,$conresaco,'la35_i_diasemana'))."','$this->la35_i_diasemana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la35_c_horaini"]) || $this->la35_c_horaini != "")
           $resac = db_query("insert into db_acount values($acount,2776,15816,'".AddSlashes(pg_result($resaco,$conresaco,'la35_c_horaini'))."','$this->la35_c_horaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la35_c_horafim"]) || $this->la35_c_horafim != "")
           $resac = db_query("insert into db_acount values($acount,2776,15817,'".AddSlashes(pg_result($resaco,$conresaco,'la35_c_horafim'))."','$this->la35_c_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la35_d_valinicio"]) || $this->la35_d_valinicio != "")
           $resac = db_query("insert into db_acount values($acount,2776,15818,'".AddSlashes(pg_result($resaco,$conresaco,'la35_d_valinicio'))."','$this->la35_d_valinicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la35_d_valfim"]) || $this->la35_d_valfim != "")
           $resac = db_query("insert into db_acount values($acount,2776,15819,'".AddSlashes(pg_result($resaco,$conresaco,'la35_d_valfim'))."','$this->la35_d_valfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_horario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la35_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_horario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la35_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la35_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la35_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la35_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15811,'$la35_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2776,15811,'','".AddSlashes(pg_result($resaco,$iresaco,'la35_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2776,15812,'','".AddSlashes(pg_result($resaco,$iresaco,'la35_i_setorexame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2776,15814,'','".AddSlashes(pg_result($resaco,$iresaco,'la35_i_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2776,15816,'','".AddSlashes(pg_result($resaco,$iresaco,'la35_c_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2776,15817,'','".AddSlashes(pg_result($resaco,$iresaco,'la35_c_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2776,15818,'','".AddSlashes(pg_result($resaco,$iresaco,'la35_d_valinicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2776,15819,'','".AddSlashes(pg_result($resaco,$iresaco,'la35_d_valfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_horario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la35_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la35_i_codigo = $la35_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_horario nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la35_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_horario nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la35_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la35_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_horario";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la35_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_horario ";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_horario.la35_i_setorexame";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = lab_horario.la35_i_diasemana";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
     $sql .= "      inner join lab_labsetor  on  lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
     $sql2 = "";
     if($dbwhere==""){
       if($la35_i_codigo!=null ){
         $sql2 .= " where lab_horario.la35_i_codigo = $la35_i_codigo "; 
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
   function sql_query_file ( $la35_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_horario ";
     $sql2 = "";
     if($dbwhere==""){
       if($la35_i_codigo!=null ){
         $sql2 .= " where lab_horario.la35_i_codigo = $la35_i_codigo "; 
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
   function sql_query_laboratorio ( $la35_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from lab_horario ";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_horario.la35_i_setorexame";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = lab_horario.la35_i_diasemana";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = lab_setorexame.la09_i_exame";
     $sql .= "      inner join lab_labsetor  on  lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
     $sql .= "      inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_labsetor.la24_i_laboratorio";
     
     $sql2 = "";
     if($dbwhere==""){
       if($la35_i_codigo!=null ){
         $sql2 .= " where lab_horario.la35_i_codigo = $la35_i_codigo ";
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