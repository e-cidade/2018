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

//MODULO: teleatend
//CLASSE DA ENTIDADE bomov
class cl_bomov { 
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
   var $bo04_codmov = 0; 
   var $bo04_codbo = 0; 
   var $bo04_datamov_dia = null; 
   var $bo04_datamov_mes = null; 
   var $bo04_datamov_ano = null; 
   var $bo04_datamov = null; 
   var $bo04_coddepto_ori = 0; 
   var $bo04_coddepto_dest = 0; 
   var $bo04_entrada = null; 
   var $bo04_saida = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 bo04_codmov = int4 = Número do movimento 
                 bo04_codbo = int4 = Número do BO 
                 bo04_datamov = date = Data do movimento 
                 bo04_coddepto_ori = int4 = Departamento de Origem 
                 bo04_coddepto_dest = int4 = Departamento de Destino 
                 bo04_entrada = varchar(3) = Entrada no Setor 
                 bo04_saida = varchar(3) = Saida do Setor 
                 ";
   //funcao construtor da classe 
   function cl_bomov() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bomov"); 
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
       $this->bo04_codmov = ($this->bo04_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["bo04_codmov"]:$this->bo04_codmov);
       $this->bo04_codbo = ($this->bo04_codbo == ""?@$GLOBALS["HTTP_POST_VARS"]["bo04_codbo"]:$this->bo04_codbo);
       if($this->bo04_datamov == ""){
         $this->bo04_datamov_dia = ($this->bo04_datamov_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bo04_datamov_dia"]:$this->bo04_datamov_dia);
         $this->bo04_datamov_mes = ($this->bo04_datamov_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bo04_datamov_mes"]:$this->bo04_datamov_mes);
         $this->bo04_datamov_ano = ($this->bo04_datamov_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bo04_datamov_ano"]:$this->bo04_datamov_ano);
         if($this->bo04_datamov_dia != ""){
            $this->bo04_datamov = $this->bo04_datamov_ano."-".$this->bo04_datamov_mes."-".$this->bo04_datamov_dia;
         }
       }
       $this->bo04_coddepto_ori = ($this->bo04_coddepto_ori == ""?@$GLOBALS["HTTP_POST_VARS"]["bo04_coddepto_ori"]:$this->bo04_coddepto_ori);
       $this->bo04_coddepto_dest = ($this->bo04_coddepto_dest == ""?@$GLOBALS["HTTP_POST_VARS"]["bo04_coddepto_dest"]:$this->bo04_coddepto_dest);
       $this->bo04_entrada = ($this->bo04_entrada == ""?@$GLOBALS["HTTP_POST_VARS"]["bo04_entrada"]:$this->bo04_entrada);
       $this->bo04_saida = ($this->bo04_saida == ""?@$GLOBALS["HTTP_POST_VARS"]["bo04_saida"]:$this->bo04_saida);
     }else{
       $this->bo04_codmov = ($this->bo04_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["bo04_codmov"]:$this->bo04_codmov);
     }
   }
   // funcao para inclusao
   function incluir ($bo04_codmov){ 
      $this->atualizacampos();
     if($this->bo04_codbo == null ){ 
       $this->erro_sql = " Campo Número do BO nao Informado.";
       $this->erro_campo = "bo04_codbo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bo04_datamov == null ){ 
       $this->erro_sql = " Campo Data do movimento nao Informado.";
       $this->erro_campo = "bo04_datamov_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bo04_coddepto_ori == null ){ 
       $this->erro_sql = " Campo Departamento de Origem nao Informado.";
       $this->erro_campo = "bo04_coddepto_ori";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bo04_coddepto_dest == null ){ 
       $this->erro_sql = " Campo Departamento de Destino nao Informado.";
       $this->erro_campo = "bo04_coddepto_dest";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bo04_entrada == null ){ 
       $this->erro_sql = " Campo Entrada no Setor nao Informado.";
       $this->erro_campo = "bo04_entrada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bo04_saida == null ){ 
       $this->erro_sql = " Campo Saida do Setor nao Informado.";
       $this->erro_campo = "bo04_saida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($bo04_codmov == "" || $bo04_codmov == null ){
       $result = db_query("select nextval('tel_codmov_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tel_codmov_seq do campo: bo04_codmov"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->bo04_codmov = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tel_codmov_seq");
       if(($result != false) && (pg_result($result,0,0) < $bo04_codmov)){
         $this->erro_sql = " Campo bo04_codmov maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bo04_codmov = $bo04_codmov; 
       }
     }
     if(($this->bo04_codmov == null) || ($this->bo04_codmov == "") ){ 
       $this->erro_sql = " Campo bo04_codmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bomov(
                                       bo04_codmov 
                                      ,bo04_codbo 
                                      ,bo04_datamov 
                                      ,bo04_coddepto_ori 
                                      ,bo04_coddepto_dest 
                                      ,bo04_entrada 
                                      ,bo04_saida 
                       )
                values (
                                $this->bo04_codmov 
                               ,$this->bo04_codbo 
                               ,".($this->bo04_datamov == "null" || $this->bo04_datamov == ""?"null":"'".$this->bo04_datamov."'")." 
                               ,$this->bo04_coddepto_ori 
                               ,$this->bo04_coddepto_dest 
                               ,'$this->bo04_entrada' 
                               ,'$this->bo04_saida' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimento do BO ($this->bo04_codmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimento do BO já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimento do BO ($this->bo04_codmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bo04_codmov;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->bo04_codmov));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8589,'$this->bo04_codmov','I')");
       $resac = db_query("insert into db_acount values($acount,1461,8589,'','".AddSlashes(pg_result($resaco,0,'bo04_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1461,8590,'','".AddSlashes(pg_result($resaco,0,'bo04_codbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1461,8591,'','".AddSlashes(pg_result($resaco,0,'bo04_datamov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1461,8592,'','".AddSlashes(pg_result($resaco,0,'bo04_coddepto_ori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1461,8593,'','".AddSlashes(pg_result($resaco,0,'bo04_coddepto_dest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1461,8594,'','".AddSlashes(pg_result($resaco,0,'bo04_entrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1461,8595,'','".AddSlashes(pg_result($resaco,0,'bo04_saida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($bo04_codmov=null) { 
      $this->atualizacampos();
     $sql = " update bomov set ";
     $virgula = "";
     if(trim($this->bo04_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo04_codmov"])){ 
       $sql  .= $virgula." bo04_codmov = $this->bo04_codmov ";
       $virgula = ",";
       if(trim($this->bo04_codmov) == null ){ 
         $this->erro_sql = " Campo Número do movimento nao Informado.";
         $this->erro_campo = "bo04_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bo04_codbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo04_codbo"])){ 
       $sql  .= $virgula." bo04_codbo = $this->bo04_codbo ";
       $virgula = ",";
       if(trim($this->bo04_codbo) == null ){ 
         $this->erro_sql = " Campo Número do BO nao Informado.";
         $this->erro_campo = "bo04_codbo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bo04_datamov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo04_datamov_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bo04_datamov_dia"] !="") ){ 
       $sql  .= $virgula." bo04_datamov = '$this->bo04_datamov' ";
       $virgula = ",";
       if(trim($this->bo04_datamov) == null ){ 
         $this->erro_sql = " Campo Data do movimento nao Informado.";
         $this->erro_campo = "bo04_datamov_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["bo04_datamov_dia"])){ 
         $sql  .= $virgula." bo04_datamov = null ";
         $virgula = ",";
         if(trim($this->bo04_datamov) == null ){ 
           $this->erro_sql = " Campo Data do movimento nao Informado.";
           $this->erro_campo = "bo04_datamov_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->bo04_coddepto_ori)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo04_coddepto_ori"])){ 
       $sql  .= $virgula." bo04_coddepto_ori = $this->bo04_coddepto_ori ";
       $virgula = ",";
       if(trim($this->bo04_coddepto_ori) == null ){ 
         $this->erro_sql = " Campo Departamento de Origem nao Informado.";
         $this->erro_campo = "bo04_coddepto_ori";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bo04_coddepto_dest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo04_coddepto_dest"])){ 
       $sql  .= $virgula." bo04_coddepto_dest = $this->bo04_coddepto_dest ";
       $virgula = ",";
       if(trim($this->bo04_coddepto_dest) == null ){ 
         $this->erro_sql = " Campo Departamento de Destino nao Informado.";
         $this->erro_campo = "bo04_coddepto_dest";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bo04_entrada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo04_entrada"])){ 
       $sql  .= $virgula." bo04_entrada = '$this->bo04_entrada' ";
       $virgula = ",";
       if(trim($this->bo04_entrada) == null ){ 
         $this->erro_sql = " Campo Entrada no Setor nao Informado.";
         $this->erro_campo = "bo04_entrada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bo04_saida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo04_saida"])){ 
       $sql  .= $virgula." bo04_saida = '$this->bo04_saida' ";
       $virgula = ",";
       if(trim($this->bo04_saida) == null ){ 
         $this->erro_sql = " Campo Saida do Setor nao Informado.";
         $this->erro_campo = "bo04_saida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($bo04_codmov!=null){
       $sql .= " bo04_codmov = $this->bo04_codmov";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->bo04_codmov));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8589,'$this->bo04_codmov','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo04_codmov"]))
           $resac = db_query("insert into db_acount values($acount,1461,8589,'".AddSlashes(pg_result($resaco,$conresaco,'bo04_codmov'))."','$this->bo04_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo04_codbo"]))
           $resac = db_query("insert into db_acount values($acount,1461,8590,'".AddSlashes(pg_result($resaco,$conresaco,'bo04_codbo'))."','$this->bo04_codbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo04_datamov"]))
           $resac = db_query("insert into db_acount values($acount,1461,8591,'".AddSlashes(pg_result($resaco,$conresaco,'bo04_datamov'))."','$this->bo04_datamov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo04_coddepto_ori"]))
           $resac = db_query("insert into db_acount values($acount,1461,8592,'".AddSlashes(pg_result($resaco,$conresaco,'bo04_coddepto_ori'))."','$this->bo04_coddepto_ori',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo04_coddepto_dest"]))
           $resac = db_query("insert into db_acount values($acount,1461,8593,'".AddSlashes(pg_result($resaco,$conresaco,'bo04_coddepto_dest'))."','$this->bo04_coddepto_dest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo04_entrada"]))
           $resac = db_query("insert into db_acount values($acount,1461,8594,'".AddSlashes(pg_result($resaco,$conresaco,'bo04_entrada'))."','$this->bo04_entrada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo04_saida"]))
           $resac = db_query("insert into db_acount values($acount,1461,8595,'".AddSlashes(pg_result($resaco,$conresaco,'bo04_saida'))."','$this->bo04_saida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimento do BO nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bo04_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimento do BO nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bo04_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bo04_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($bo04_codmov=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($bo04_codmov));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8589,'$bo04_codmov','E')");
         $resac = db_query("insert into db_acount values($acount,1461,8589,'','".AddSlashes(pg_result($resaco,$iresaco,'bo04_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1461,8590,'','".AddSlashes(pg_result($resaco,$iresaco,'bo04_codbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1461,8591,'','".AddSlashes(pg_result($resaco,$iresaco,'bo04_datamov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1461,8592,'','".AddSlashes(pg_result($resaco,$iresaco,'bo04_coddepto_ori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1461,8593,'','".AddSlashes(pg_result($resaco,$iresaco,'bo04_coddepto_dest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1461,8594,'','".AddSlashes(pg_result($resaco,$iresaco,'bo04_entrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1461,8595,'','".AddSlashes(pg_result($resaco,$iresaco,'bo04_saida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bomov
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($bo04_codmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " bo04_codmov = $bo04_codmov ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimento do BO nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bo04_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimento do BO nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bo04_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bo04_codmov;
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
        $this->erro_sql   = "Record Vazio na Tabela:bomov";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $bo04_codmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bomov ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bomov.bo04_coddepto_ori";
     $sql .= "      inner join bo  on  bo.bo01_codbo = bomov.bo04_codbo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bo.bo01_numcgm";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = bo.bo01_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($bo04_codmov!=null ){
         $sql2 .= " where bomov.bo04_codmov = $bo04_codmov "; 
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
   function sql_query_file ( $bo04_codmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bomov ";
     $sql2 = "";
     if($dbwhere==""){
       if($bo04_codmov!=null ){
         $sql2 .= " where bomov.bo04_codmov = $bo04_codmov "; 
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