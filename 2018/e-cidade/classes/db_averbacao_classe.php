<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: cadastro
//CLASSE DA ENTIDADE averbacao
class cl_averbacao { 
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
   var $j75_codigo = 0; 
   var $j75_matric = 0; 
   var $j75_data_dia = null; 
   var $j75_data_mes = null; 
   var $j75_data_ano = null; 
   var $j75_data = null; 
   var $j75_obs = null; 
   var $j75_tipo = 0; 
   var $j75_dttipo_dia = null; 
   var $j75_dttipo_mes = null; 
   var $j75_dttipo_ano = null; 
   var $j75_dttipo = null; 
   var $j75_situacao = 0; 
   var $j75_regra = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j75_codigo = int4 = Código Averbação 
                 j75_matric = int4 = Matrícula 
                 j75_data = date = Data da Averbação 
                 j75_obs = text = Observação 
                 j75_tipo = int4 = Tipo 
                 j75_dttipo = date = Data do Tipo 
                 j75_situacao = int4 = Situação 
                 j75_regra = int4 = Regra usada para averbar conforme tipo 
                 ";
   //funcao construtor da classe 
   function cl_averbacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("averbacao"); 
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
       $this->j75_codigo = ($this->j75_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j75_codigo"]:$this->j75_codigo);
       $this->j75_matric = ($this->j75_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j75_matric"]:$this->j75_matric);
       if($this->j75_data == ""){
         $this->j75_data_dia = ($this->j75_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j75_data_dia"]:$this->j75_data_dia);
         $this->j75_data_mes = ($this->j75_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j75_data_mes"]:$this->j75_data_mes);
         $this->j75_data_ano = ($this->j75_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j75_data_ano"]:$this->j75_data_ano);
         if($this->j75_data_dia != ""){
            $this->j75_data = $this->j75_data_ano."-".$this->j75_data_mes."-".$this->j75_data_dia;
         }
       }
       $this->j75_obs = ($this->j75_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["j75_obs"]:$this->j75_obs);
       $this->j75_tipo = ($this->j75_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["j75_tipo"]:$this->j75_tipo);
       if($this->j75_dttipo == ""){
         $this->j75_dttipo_dia = ($this->j75_dttipo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j75_dttipo_dia"]:$this->j75_dttipo_dia);
         $this->j75_dttipo_mes = ($this->j75_dttipo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j75_dttipo_mes"]:$this->j75_dttipo_mes);
         $this->j75_dttipo_ano = ($this->j75_dttipo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j75_dttipo_ano"]:$this->j75_dttipo_ano);
         if($this->j75_dttipo_dia != ""){
            $this->j75_dttipo = $this->j75_dttipo_ano."-".$this->j75_dttipo_mes."-".$this->j75_dttipo_dia;
         }
       }
       $this->j75_situacao = ($this->j75_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["j75_situacao"]:$this->j75_situacao);
       $this->j75_regra = ($this->j75_regra == ""?@$GLOBALS["HTTP_POST_VARS"]["j75_regra"]:$this->j75_regra);
     }else{
       $this->j75_codigo = ($this->j75_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j75_codigo"]:$this->j75_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($j75_codigo){ 
      $this->atualizacampos();
     if($this->j75_matric == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "j75_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j75_data == null ){ 
       $this->erro_sql = " Campo Data da Averbação nao Informado.";
       $this->erro_campo = "j75_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j75_obs == null ){ 
       $this->j75_obs = "0";
     }
     if($this->j75_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "j75_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j75_dttipo == null ){ 
       $this->erro_sql = " Campo Data do Tipo nao Informado.";
       $this->erro_campo = "j75_dttipo_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j75_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "j75_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j75_regra == null ){ 
       $this->erro_sql = " Campo Regra usada para averbar conforme tipo nao Informado.";
       $this->erro_campo = "j75_regra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j75_codigo == "" || $j75_codigo == null ){
       $result = db_query("select nextval('averbacao_j75_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: averbacao_j75_codigo_seq do campo: j75_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j75_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from averbacao_j75_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $j75_codigo)){
         $this->erro_sql = " Campo j75_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j75_codigo = $j75_codigo; 
       }
     }
     if(($this->j75_codigo == null) || ($this->j75_codigo == "") ){ 
       $this->erro_sql = " Campo j75_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into averbacao(
                                       j75_codigo 
                                      ,j75_matric 
                                      ,j75_data 
                                      ,j75_obs 
                                      ,j75_tipo 
                                      ,j75_dttipo 
                                      ,j75_situacao 
                                      ,j75_regra 
                       )
                values (
                                $this->j75_codigo 
                               ,$this->j75_matric 
                               ,".($this->j75_data == "null" || $this->j75_data == ""?"null":"'".$this->j75_data."'")." 
                               ,'$this->j75_obs' 
                               ,$this->j75_tipo 
                               ,".($this->j75_dttipo == "null" || $this->j75_dttipo == ""?"null":"'".$this->j75_dttipo."'")." 
                               ,$this->j75_situacao 
                               ,$this->j75_regra 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Averbação ($this->j75_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Averbação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Averbação ($this->j75_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j75_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j75_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7778,'$this->j75_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1649,7778,'','".AddSlashes(pg_result($resaco,0,'j75_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1649,7779,'','".AddSlashes(pg_result($resaco,0,'j75_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1649,7780,'','".AddSlashes(pg_result($resaco,0,'j75_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1649,7681,'','".AddSlashes(pg_result($resaco,0,'j75_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1649,9593,'','".AddSlashes(pg_result($resaco,0,'j75_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1649,9594,'','".AddSlashes(pg_result($resaco,0,'j75_dttipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1649,9615,'','".AddSlashes(pg_result($resaco,0,'j75_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1649,10009,'','".AddSlashes(pg_result($resaco,0,'j75_regra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j75_codigo=null) { 
      $this->atualizacampos();
     $sql = " update averbacao set ";
     $virgula = "";
     if(trim($this->j75_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j75_codigo"])){ 
       $sql  .= $virgula." j75_codigo = $this->j75_codigo ";
       $virgula = ",";
       if(trim($this->j75_codigo) == null ){ 
         $this->erro_sql = " Campo Código Averbação nao Informado.";
         $this->erro_campo = "j75_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j75_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j75_matric"])){ 
       $sql  .= $virgula." j75_matric = $this->j75_matric ";
       $virgula = ",";
       if(trim($this->j75_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "j75_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j75_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j75_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j75_data_dia"] !="") ){ 
       $sql  .= $virgula." j75_data = '$this->j75_data' ";
       $virgula = ",";
       if(trim($this->j75_data) == null ){ 
         $this->erro_sql = " Campo Data da Averbação nao Informado.";
         $this->erro_campo = "j75_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j75_data_dia"])){ 
         $sql  .= $virgula." j75_data = null ";
         $virgula = ",";
         if(trim($this->j75_data) == null ){ 
           $this->erro_sql = " Campo Data da Averbação nao Informado.";
           $this->erro_campo = "j75_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j75_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j75_obs"])){ 
       $sql  .= $virgula." j75_obs = '$this->j75_obs' ";
       $virgula = ",";
     }
     if(trim($this->j75_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j75_tipo"])){ 
       $sql  .= $virgula." j75_tipo = $this->j75_tipo ";
       $virgula = ",";
       if(trim($this->j75_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "j75_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j75_dttipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j75_dttipo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j75_dttipo_dia"] !="") ){ 
       $sql  .= $virgula." j75_dttipo = '$this->j75_dttipo' ";
       $virgula = ",";
       if(trim($this->j75_dttipo) == null ){ 
         $this->erro_sql = " Campo Data do Tipo nao Informado.";
         $this->erro_campo = "j75_dttipo_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j75_dttipo_dia"])){ 
         $sql  .= $virgula." j75_dttipo = null ";
         $virgula = ",";
         if(trim($this->j75_dttipo) == null ){ 
           $this->erro_sql = " Campo Data do Tipo nao Informado.";
           $this->erro_campo = "j75_dttipo_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j75_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j75_situacao"])){ 
       $sql  .= $virgula." j75_situacao = $this->j75_situacao ";
       $virgula = ",";
       if(trim($this->j75_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "j75_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j75_regra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j75_regra"])){ 
       $sql  .= $virgula." j75_regra = $this->j75_regra ";
       $virgula = ",";
       if(trim($this->j75_regra) == null ){ 
         $this->erro_sql = " Campo Regra usada para averbar conforme tipo nao Informado.";
         $this->erro_campo = "j75_regra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j75_codigo!=null){
       $sql .= " j75_codigo = $this->j75_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j75_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7778,'$this->j75_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j75_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1649,7778,'".AddSlashes(pg_result($resaco,$conresaco,'j75_codigo'))."','$this->j75_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j75_matric"]))
           $resac = db_query("insert into db_acount values($acount,1649,7779,'".AddSlashes(pg_result($resaco,$conresaco,'j75_matric'))."','$this->j75_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j75_data"]))
           $resac = db_query("insert into db_acount values($acount,1649,7780,'".AddSlashes(pg_result($resaco,$conresaco,'j75_data'))."','$this->j75_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j75_obs"]))
           $resac = db_query("insert into db_acount values($acount,1649,7681,'".AddSlashes(pg_result($resaco,$conresaco,'j75_obs'))."','$this->j75_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j75_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1649,9593,'".AddSlashes(pg_result($resaco,$conresaco,'j75_tipo'))."','$this->j75_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j75_dttipo"]))
           $resac = db_query("insert into db_acount values($acount,1649,9594,'".AddSlashes(pg_result($resaco,$conresaco,'j75_dttipo'))."','$this->j75_dttipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j75_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1649,9615,'".AddSlashes(pg_result($resaco,$conresaco,'j75_situacao'))."','$this->j75_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j75_regra"]))
           $resac = db_query("insert into db_acount values($acount,1649,10009,'".AddSlashes(pg_result($resaco,$conresaco,'j75_regra'))."','$this->j75_regra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Averbação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j75_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Averbação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j75_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j75_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j75_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j75_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7778,'$j75_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1649,7778,'','".AddSlashes(pg_result($resaco,$iresaco,'j75_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1649,7779,'','".AddSlashes(pg_result($resaco,$iresaco,'j75_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1649,7780,'','".AddSlashes(pg_result($resaco,$iresaco,'j75_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1649,7681,'','".AddSlashes(pg_result($resaco,$iresaco,'j75_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1649,9593,'','".AddSlashes(pg_result($resaco,$iresaco,'j75_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1649,9594,'','".AddSlashes(pg_result($resaco,$iresaco,'j75_dttipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1649,9615,'','".AddSlashes(pg_result($resaco,$iresaco,'j75_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1649,10009,'','".AddSlashes(pg_result($resaco,$iresaco,'j75_regra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from averbacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j75_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j75_codigo = $j75_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Averbação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j75_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Averbação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j75_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j75_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:averbacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j75_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from averbacao ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = averbacao.j75_matric";
     $sql .= "      inner join averbatipo  on  averbatipo.j93_codigo = averbacao.j75_tipo";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($j75_codigo!=null ){
         $sql2 .= " where averbacao.j75_codigo = $j75_codigo "; 
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
   function sql_query_file ( $j75_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from averbacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($j75_codigo!=null ){
         $sql2 .= " where averbacao.j75_codigo = $j75_codigo "; 
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
  
  function sql_query_loteloc ( $j75_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
    $sql .= " from averbacao ";
    $sql .= "      inner join iptubase   on iptubase.j01_matric   = averbacao.j75_matric";
    $sql .= "      inner join averbatipo on averbatipo.j93_codigo = averbacao.j75_tipo";
    $sql .= "      inner join lote       on lote.j34_idbql        = iptubase.j01_idbql";
    $sql .= "      inner join cgm        on cgm.z01_numcgm        = iptubase.j01_numcgm";
    $sql .= "       left join loteloc    on loteloc.j06_idbql     = iptubase.j01_idbql";
    $sql .= "       left join setorloc   on setorloc.j05_codigo   = loteloc.j06_setorloc";
    $sql2 = "";
    if($dbwhere==""){
      if($j75_codigo!=null ){
        $sql2 .= " where averbacao.j75_codigo = $j75_codigo "; 
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