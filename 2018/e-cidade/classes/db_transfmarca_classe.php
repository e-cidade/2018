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

//MODULO: marcas
//CLASSE DA ENTIDADE transfmarca
class cl_transfmarca { 
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
   var $ma02_i_codigo = 0; 
   var $ma02_i_marca = 0; 
   var $ma02_i_propant = 0; 
   var $ma02_i_propnovo = 0; 
   var $ma02_i_codproc = 0; 
   var $ma02_d_data_dia = null; 
   var $ma02_d_data_mes = null; 
   var $ma02_d_data_ano = null; 
   var $ma02_d_data = null; 
   var $ma02_t_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ma02_i_codigo = int4 = Código 
                 ma02_i_marca = int4 = Código da Marca 
                 ma02_i_propant = int4 = Cgm Propriet Anterior 
                 ma02_i_propnovo = int4 = Cgm Propriet Novo 
                 ma02_i_codproc = int4 = Código do processo 
                 ma02_d_data = date = Data 
                 ma02_t_obs = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_transfmarca() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("transfmarca"); 
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
       $this->ma02_i_codigo = ($this->ma02_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ma02_i_codigo"]:$this->ma02_i_codigo);
       $this->ma02_i_marca = ($this->ma02_i_marca == ""?@$GLOBALS["HTTP_POST_VARS"]["ma02_i_marca"]:$this->ma02_i_marca);
       $this->ma02_i_propant = ($this->ma02_i_propant == ""?@$GLOBALS["HTTP_POST_VARS"]["ma02_i_propant"]:$this->ma02_i_propant);
       $this->ma02_i_propnovo = ($this->ma02_i_propnovo == ""?@$GLOBALS["HTTP_POST_VARS"]["ma02_i_propnovo"]:$this->ma02_i_propnovo);
       $this->ma02_i_codproc = ($this->ma02_i_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["ma02_i_codproc"]:$this->ma02_i_codproc);
       if($this->ma02_d_data == ""){
         $this->ma02_d_data_dia = ($this->ma02_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ma02_d_data_dia"]:$this->ma02_d_data_dia);
         $this->ma02_d_data_mes = ($this->ma02_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ma02_d_data_mes"]:$this->ma02_d_data_mes);
         $this->ma02_d_data_ano = ($this->ma02_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ma02_d_data_ano"]:$this->ma02_d_data_ano);
         if($this->ma02_d_data_dia != ""){
            $this->ma02_d_data = $this->ma02_d_data_ano."-".$this->ma02_d_data_mes."-".$this->ma02_d_data_dia;
         }
       }
       $this->ma02_t_obs = ($this->ma02_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ma02_t_obs"]:$this->ma02_t_obs);
     }else{
       $this->ma02_i_codigo = ($this->ma02_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ma02_i_codigo"]:$this->ma02_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ma02_i_codigo){ 
      $this->atualizacampos();
     if($this->ma02_i_marca == null ){ 
       $this->erro_sql = " Campo Código da Marca nao Informado.";
       $this->erro_campo = "ma02_i_marca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ma02_i_propant == null ){ 
       $this->erro_sql = " Campo Cgm Propriet Anterior nao Informado.";
       $this->erro_campo = "ma02_i_propant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ma02_i_propnovo == null ){ 
       $this->erro_sql = " Campo Cgm Propriet Novo nao Informado.";
       $this->erro_campo = "ma02_i_propnovo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ma02_i_codproc == null ){ 
       $this->erro_sql = " Campo Código do processo nao Informado.";
       $this->erro_campo = "ma02_i_codproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ma02_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ma02_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ma02_t_obs == null ){ 
       $this->erro_sql = " Campo Observações nao Informado.";
       $this->erro_campo = "ma02_t_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ma02_i_codigo == "" || $ma02_i_codigo == null ){
       $result = db_query("select nextval('transfmarca_ma02_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: transfmarca_ma02_i_codigo_seq do campo: ma02_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ma02_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from transfmarca_ma02_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ma02_i_codigo)){
         $this->erro_sql = " Campo ma02_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ma02_i_codigo = $ma02_i_codigo; 
       }
     }
     if(($this->ma02_i_codigo == null) || ($this->ma02_i_codigo == "") ){ 
       $this->erro_sql = " Campo ma02_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into transfmarca(
                                       ma02_i_codigo 
                                      ,ma02_i_marca 
                                      ,ma02_i_propant 
                                      ,ma02_i_propnovo 
                                      ,ma02_i_codproc 
                                      ,ma02_d_data 
                                      ,ma02_t_obs 
                       )
                values (
                                $this->ma02_i_codigo 
                               ,$this->ma02_i_marca 
                               ,$this->ma02_i_propant 
                               ,$this->ma02_i_propnovo 
                               ,$this->ma02_i_codproc 
                               ,".($this->ma02_d_data == "null" || $this->ma02_d_data == ""?"null":"'".$this->ma02_d_data."'")." 
                               ,'$this->ma02_t_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "resgistro de transferencia de marcas ($this->ma02_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "resgistro de transferencia de marcas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "resgistro de transferencia de marcas ($this->ma02_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ma02_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ma02_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10519,'$this->ma02_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1816,10519,'','".AddSlashes(pg_result($resaco,0,'ma02_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1816,10520,'','".AddSlashes(pg_result($resaco,0,'ma02_i_marca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1816,10521,'','".AddSlashes(pg_result($resaco,0,'ma02_i_propant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1816,10522,'','".AddSlashes(pg_result($resaco,0,'ma02_i_propnovo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1816,10523,'','".AddSlashes(pg_result($resaco,0,'ma02_i_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1816,10524,'','".AddSlashes(pg_result($resaco,0,'ma02_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1816,10525,'','".AddSlashes(pg_result($resaco,0,'ma02_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ma02_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update transfmarca set ";
     $virgula = "";
     if(trim($this->ma02_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma02_i_codigo"])){ 
       $sql  .= $virgula." ma02_i_codigo = $this->ma02_i_codigo ";
       $virgula = ",";
       if(trim($this->ma02_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ma02_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ma02_i_marca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma02_i_marca"])){ 
       $sql  .= $virgula." ma02_i_marca = $this->ma02_i_marca ";
       $virgula = ",";
       if(trim($this->ma02_i_marca) == null ){ 
         $this->erro_sql = " Campo Código da Marca nao Informado.";
         $this->erro_campo = "ma02_i_marca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ma02_i_propant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma02_i_propant"])){ 
       $sql  .= $virgula." ma02_i_propant = $this->ma02_i_propant ";
       $virgula = ",";
       if(trim($this->ma02_i_propant) == null ){ 
         $this->erro_sql = " Campo Cgm Propriet Anterior nao Informado.";
         $this->erro_campo = "ma02_i_propant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ma02_i_propnovo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma02_i_propnovo"])){ 
       $sql  .= $virgula." ma02_i_propnovo = $this->ma02_i_propnovo ";
       $virgula = ",";
       if(trim($this->ma02_i_propnovo) == null ){ 
         $this->erro_sql = " Campo Cgm Propriet Novo nao Informado.";
         $this->erro_campo = "ma02_i_propnovo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ma02_i_codproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma02_i_codproc"])){ 
       $sql  .= $virgula." ma02_i_codproc = $this->ma02_i_codproc ";
       $virgula = ",";
       if(trim($this->ma02_i_codproc) == null ){ 
         $this->erro_sql = " Campo Código do processo nao Informado.";
         $this->erro_campo = "ma02_i_codproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ma02_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma02_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ma02_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ma02_d_data = '$this->ma02_d_data' ";
       $virgula = ",";
       if(trim($this->ma02_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ma02_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ma02_d_data_dia"])){ 
         $sql  .= $virgula." ma02_d_data = null ";
         $virgula = ",";
         if(trim($this->ma02_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ma02_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ma02_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma02_t_obs"])){ 
       $sql  .= $virgula." ma02_t_obs = '$this->ma02_t_obs' ";
       $virgula = ",";
       if(trim($this->ma02_t_obs) == null ){ 
         $this->erro_sql = " Campo Observações nao Informado.";
         $this->erro_campo = "ma02_t_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ma02_i_codigo!=null){
       $sql .= " ma02_i_codigo = $this->ma02_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ma02_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10519,'$this->ma02_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma02_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1816,10519,'".AddSlashes(pg_result($resaco,$conresaco,'ma02_i_codigo'))."','$this->ma02_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma02_i_marca"]))
           $resac = db_query("insert into db_acount values($acount,1816,10520,'".AddSlashes(pg_result($resaco,$conresaco,'ma02_i_marca'))."','$this->ma02_i_marca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma02_i_propant"]))
           $resac = db_query("insert into db_acount values($acount,1816,10521,'".AddSlashes(pg_result($resaco,$conresaco,'ma02_i_propant'))."','$this->ma02_i_propant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma02_i_propnovo"]))
           $resac = db_query("insert into db_acount values($acount,1816,10522,'".AddSlashes(pg_result($resaco,$conresaco,'ma02_i_propnovo'))."','$this->ma02_i_propnovo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma02_i_codproc"]))
           $resac = db_query("insert into db_acount values($acount,1816,10523,'".AddSlashes(pg_result($resaco,$conresaco,'ma02_i_codproc'))."','$this->ma02_i_codproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma02_d_data"]))
           $resac = db_query("insert into db_acount values($acount,1816,10524,'".AddSlashes(pg_result($resaco,$conresaco,'ma02_d_data'))."','$this->ma02_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma02_t_obs"]))
           $resac = db_query("insert into db_acount values($acount,1816,10525,'".AddSlashes(pg_result($resaco,$conresaco,'ma02_t_obs'))."','$this->ma02_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "resgistro de transferencia de marcas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ma02_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "resgistro de transferencia de marcas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ma02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ma02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ma02_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ma02_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10519,'$ma02_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1816,10519,'','".AddSlashes(pg_result($resaco,$iresaco,'ma02_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1816,10520,'','".AddSlashes(pg_result($resaco,$iresaco,'ma02_i_marca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1816,10521,'','".AddSlashes(pg_result($resaco,$iresaco,'ma02_i_propant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1816,10522,'','".AddSlashes(pg_result($resaco,$iresaco,'ma02_i_propnovo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1816,10523,'','".AddSlashes(pg_result($resaco,$iresaco,'ma02_i_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1816,10524,'','".AddSlashes(pg_result($resaco,$iresaco,'ma02_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1816,10525,'','".AddSlashes(pg_result($resaco,$iresaco,'ma02_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from transfmarca
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ma02_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ma02_i_codigo = $ma02_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "resgistro de transferencia de marcas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ma02_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "resgistro de transferencia de marcas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ma02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ma02_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:transfmarca";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ma02_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from transfmarca ";
     $sql .= "      inner join cgm as cgm1 on  cgm1.z01_numcgm = transfmarca.ma02_i_propant ";
     $sql .= "      inner join cgm as cgm4 on  cgm4.z01_numcgm = transfmarca.ma02_i_propnovo ";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = transfmarca.ma02_i_codproc";
     $sql .= "      inner join marca  on  marca.ma01_i_codigo = transfmarca.ma02_i_marca";
     $sql .= "      inner join cgm as cgm2 on  cgm2.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql .= "      inner join cgm as cgm3 on  cgm3.z01_numcgm = marca.ma01_i_cgm";
     //$sql .= "      inner join localmarca  on  localmarca.ma04_i_codigo = marca.ma01_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ma02_i_codigo!=null ){
         $sql2 .= " where transfmarca.ma02_i_codigo = $ma02_i_codigo "; 
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
   function sql_query_file ( $ma02_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from transfmarca ";
     $sql2 = "";
     if($dbwhere==""){
       if($ma02_i_codigo!=null ){
         $sql2 .= " where transfmarca.ma02_i_codigo = $ma02_i_codigo "; 
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