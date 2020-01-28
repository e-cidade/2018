<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: protocolo
//CLASSE DA ENTIDADE proctransfer
class cl_proctransfer { 
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
   var $p62_codtran = 0; 
   var $p62_dttran_dia = null; 
   var $p62_dttran_mes = null; 
   var $p62_dttran_ano = null; 
   var $p62_dttran = null; 
   var $p62_id_usuario = 0; 
   var $p62_coddepto = 0; 
   var $p62_id_usorec = 0; 
   var $p62_coddeptorec = 0; 
   var $p62_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p62_codtran = int4 = Transferência 
                 p62_dttran = date = Data 
                 p62_id_usuario = int4 = id usuario 
                 p62_coddepto = int4 = Departamento 
                 p62_id_usorec = int4 = recebimento 
                 p62_coddeptorec = int4 = Departamento 
                 p62_hora = varchar(5) = Hora da transferencia 
                 ";
   //funcao construtor da classe 
   function cl_proctransfer() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("proctransfer"); 
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
       $this->p62_codtran = ($this->p62_codtran == ""?@$GLOBALS["HTTP_POST_VARS"]["p62_codtran"]:$this->p62_codtran);
       if($this->p62_dttran == ""){
         $this->p62_dttran_dia = ($this->p62_dttran_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p62_dttran_dia"]:$this->p62_dttran_dia);
         $this->p62_dttran_mes = ($this->p62_dttran_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p62_dttran_mes"]:$this->p62_dttran_mes);
         $this->p62_dttran_ano = ($this->p62_dttran_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p62_dttran_ano"]:$this->p62_dttran_ano);
         if($this->p62_dttran_dia != ""){
            $this->p62_dttran = $this->p62_dttran_ano."-".$this->p62_dttran_mes."-".$this->p62_dttran_dia;
         }
       }
       $this->p62_id_usuario = ($this->p62_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["p62_id_usuario"]:$this->p62_id_usuario);
       $this->p62_coddepto = ($this->p62_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["p62_coddepto"]:$this->p62_coddepto);
       $this->p62_id_usorec = ($this->p62_id_usorec == ""?@$GLOBALS["HTTP_POST_VARS"]["p62_id_usorec"]:$this->p62_id_usorec);
       $this->p62_coddeptorec = ($this->p62_coddeptorec == ""?@$GLOBALS["HTTP_POST_VARS"]["p62_coddeptorec"]:$this->p62_coddeptorec);
       $this->p62_hora = ($this->p62_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["p62_hora"]:$this->p62_hora);
     }else{
       $this->p62_codtran = ($this->p62_codtran == ""?@$GLOBALS["HTTP_POST_VARS"]["p62_codtran"]:$this->p62_codtran);
     }
   }
   // funcao para inclusao
   function incluir ($p62_codtran){ 
      $this->atualizacampos();
     if($this->p62_dttran == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "p62_dttran_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p62_id_usuario == null ){ 
       $this->erro_sql = " Campo id usuario nao Informado.";
       $this->erro_campo = "p62_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p62_coddepto == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "p62_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p62_id_usorec == null ){ 
       $this->erro_sql = " Campo recebimento nao Informado.";
       $this->erro_campo = "p62_id_usorec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p62_coddeptorec == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "p62_coddeptorec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p62_codtran == "" || $p62_codtran == null ){
       $result = db_query("select nextval('proctransfer_p62_codtran_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: proctransfer_p62_codtran_seq do campo: p62_codtran"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p62_codtran = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from proctransfer_p62_codtran_seq");
       if(($result != false) && (pg_result($result,0,0) < $p62_codtran)){
         $this->erro_sql = " Campo p62_codtran maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p62_codtran = $p62_codtran; 
       }
     }
     if(($this->p62_codtran == null) || ($this->p62_codtran == "") ){ 
       $this->erro_sql = " Campo p62_codtran nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into proctransfer(
                                       p62_codtran 
                                      ,p62_dttran 
                                      ,p62_id_usuario 
                                      ,p62_coddepto 
                                      ,p62_id_usorec 
                                      ,p62_coddeptorec 
                                      ,p62_hora 
                       )
                values (
                                $this->p62_codtran 
                               ,".($this->p62_dttran == "null" || $this->p62_dttran == ""?"null":"'".$this->p62_dttran."'")." 
                               ,$this->p62_id_usuario 
                               ,$this->p62_coddepto 
                               ,$this->p62_id_usorec 
                               ,$this->p62_coddeptorec 
                               ,'$this->p62_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->p62_codtran) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->p62_codtran) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p62_codtran;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p62_codtran));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2474,'$this->p62_codtran','I')");
       $resac = db_query("insert into db_acount values($acount,408,2474,'','".AddSlashes(pg_result($resaco,0,'p62_codtran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,408,2475,'','".AddSlashes(pg_result($resaco,0,'p62_dttran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,408,2476,'','".AddSlashes(pg_result($resaco,0,'p62_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,408,2477,'','".AddSlashes(pg_result($resaco,0,'p62_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,408,2478,'','".AddSlashes(pg_result($resaco,0,'p62_id_usorec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,408,2479,'','".AddSlashes(pg_result($resaco,0,'p62_coddeptorec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,408,6103,'','".AddSlashes(pg_result($resaco,0,'p62_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p62_codtran=null) { 
      $this->atualizacampos();
     $sql = " update proctransfer set ";
     $virgula = "";
     if(trim($this->p62_codtran)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p62_codtran"])){ 
       $sql  .= $virgula." p62_codtran = $this->p62_codtran ";
       $virgula = ",";
       if(trim($this->p62_codtran) == null ){ 
         $this->erro_sql = " Campo Transferência nao Informado.";
         $this->erro_campo = "p62_codtran";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p62_dttran)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p62_dttran_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p62_dttran_dia"] !="") ){ 
       $sql  .= $virgula." p62_dttran = '$this->p62_dttran' ";
       $virgula = ",";
       if(trim($this->p62_dttran) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "p62_dttran_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p62_dttran_dia"])){ 
         $sql  .= $virgula." p62_dttran = null ";
         $virgula = ",";
         if(trim($this->p62_dttran) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "p62_dttran_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->p62_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p62_id_usuario"])){ 
       $sql  .= $virgula." p62_id_usuario = $this->p62_id_usuario ";
       $virgula = ",";
       if(trim($this->p62_id_usuario) == null ){ 
         $this->erro_sql = " Campo id usuario nao Informado.";
         $this->erro_campo = "p62_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p62_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p62_coddepto"])){ 
       $sql  .= $virgula." p62_coddepto = $this->p62_coddepto ";
       $virgula = ",";
       if(trim($this->p62_coddepto) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "p62_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p62_id_usorec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p62_id_usorec"])){ 
       $sql  .= $virgula." p62_id_usorec = $this->p62_id_usorec ";
       $virgula = ",";
       if(trim($this->p62_id_usorec) == null ){ 
         $this->erro_sql = " Campo recebimento nao Informado.";
         $this->erro_campo = "p62_id_usorec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p62_coddeptorec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p62_coddeptorec"])){ 
       $sql  .= $virgula." p62_coddeptorec = $this->p62_coddeptorec ";
       $virgula = ",";
       if(trim($this->p62_coddeptorec) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "p62_coddeptorec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p62_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p62_hora"])){ 
       $sql  .= $virgula." p62_hora = '$this->p62_hora' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($p62_codtran!=null){
       $sql .= " p62_codtran = $this->p62_codtran";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p62_codtran));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2474,'$this->p62_codtran','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p62_codtran"]))
           $resac = db_query("insert into db_acount values($acount,408,2474,'".AddSlashes(pg_result($resaco,$conresaco,'p62_codtran'))."','$this->p62_codtran',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p62_dttran"]))
           $resac = db_query("insert into db_acount values($acount,408,2475,'".AddSlashes(pg_result($resaco,$conresaco,'p62_dttran'))."','$this->p62_dttran',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p62_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,408,2476,'".AddSlashes(pg_result($resaco,$conresaco,'p62_id_usuario'))."','$this->p62_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p62_coddepto"]))
           $resac = db_query("insert into db_acount values($acount,408,2477,'".AddSlashes(pg_result($resaco,$conresaco,'p62_coddepto'))."','$this->p62_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p62_id_usorec"]))
           $resac = db_query("insert into db_acount values($acount,408,2478,'".AddSlashes(pg_result($resaco,$conresaco,'p62_id_usorec'))."','$this->p62_id_usorec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p62_coddeptorec"]))
           $resac = db_query("insert into db_acount values($acount,408,2479,'".AddSlashes(pg_result($resaco,$conresaco,'p62_coddeptorec'))."','$this->p62_coddeptorec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p62_hora"]))
           $resac = db_query("insert into db_acount values($acount,408,6103,'".AddSlashes(pg_result($resaco,$conresaco,'p62_hora'))."','$this->p62_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p62_codtran;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p62_codtran;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p62_codtran;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p62_codtran=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p62_codtran));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2474,'$p62_codtran','E')");
         $resac = db_query("insert into db_acount values($acount,408,2474,'','".AddSlashes(pg_result($resaco,$iresaco,'p62_codtran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,408,2475,'','".AddSlashes(pg_result($resaco,$iresaco,'p62_dttran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,408,2476,'','".AddSlashes(pg_result($resaco,$iresaco,'p62_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,408,2477,'','".AddSlashes(pg_result($resaco,$iresaco,'p62_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,408,2478,'','".AddSlashes(pg_result($resaco,$iresaco,'p62_id_usorec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,408,2479,'','".AddSlashes(pg_result($resaco,$iresaco,'p62_coddeptorec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,408,6103,'','".AddSlashes(pg_result($resaco,$iresaco,'p62_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from proctransfer
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p62_codtran != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p62_codtran = $p62_codtran ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p62_codtran;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p62_codtran;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p62_codtran;
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
        $this->erro_sql   = "Record Vazio na Tabela:proctransfer";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $p62_codtran=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proctransfer ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = proctransfer.p62_coddepto";
		 $sql .= "      left join db_usuarios on  db_usuarios.id_usuario = proctransfer.p62_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($p62_codtran!=null ){
         $sql2 .= " where proctransfer.p62_codtran = $p62_codtran "; 
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
   function sql_query_deps ( $p62_codtran=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proctransfer ";
     $sql .= "      inner join db_depart as atual  on  atual.coddepto = proctransfer.p62_coddepto";
     $sql .= "      inner join db_config as instiatual  on  atual.instit = instiatual.codigo";
     $sql .= "      inner join db_depart as destino on  destino.coddepto = proctransfer.p62_coddeptorec";
     $sql .= "      inner join db_config as destinoinst  on  atual.instit = destinoinst.codigo";
     $sql .= "      inner join db_usuarios as usu_atual  on  usu_atual.id_usuario = proctransfer.p62_id_usuario";
     $sql .= "      left join db_usuarios as usu_destino  on  usu_destino.id_usuario = proctransfer.p62_id_usorec";
     $sql2 = "";
     if($dbwhere==""){
       if($p62_codtran!=null ){
         $sql2 .= " where proctransfer.p62_codtran = $p62_codtran "; 
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
   function sql_query_file ( $p62_codtran=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proctransfer ";
     $sql2 = "";
     if($dbwhere==""){
       if($p62_codtran!=null ){
         $sql2 .= " where proctransfer.p62_codtran = $p62_codtran "; 
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
   function sql_query_trans( $p62_codtran=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from proctransfer ";
     $sql .= "      left join proctransand on p64_codtran=p62_codtran ";
     $sql .= "      left join proctransferproc on p63_codtran=p62_codtran ";
     $sql2 = "";
     if($dbwhere==""){
       if($p62_codtran!=null ){
         $sql2 .= " where proctransfer.p62_codtran = $p62_codtran "; 
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