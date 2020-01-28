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
//CLASSE DA ENTIDADE procandam
class cl_procandam { 
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
   var $p61_codandam = 0; 
   var $p61_codproc = 0; 
   var $p61_id_usuario = 0; 
   var $p61_dtandam_dia = null; 
   var $p61_dtandam_mes = null; 
   var $p61_dtandam_ano = null; 
   var $p61_dtandam = null; 
   var $p61_despacho = null; 
   var $p61_coddepto = 0; 
   var $p61_publico = 'f'; 
   var $p61_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p61_codandam = int4 = Código andamento 
                 p61_codproc = int4 = Processo 
                 p61_id_usuario = int4 = id do usuario 
                 p61_dtandam = date = Data 
                 p61_despacho = text = Parecer 
                 p61_coddepto = int4 = departamento 
                 p61_publico = bool = Despacho Publico 
                 p61_hora = varchar(5) = Hora 
                 ";
   //funcao construtor da classe 
   function cl_procandam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procandam"); 
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
       $this->p61_codandam = ($this->p61_codandam == ""?@$GLOBALS["HTTP_POST_VARS"]["p61_codandam"]:$this->p61_codandam);
       $this->p61_codproc = ($this->p61_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["p61_codproc"]:$this->p61_codproc);
       $this->p61_id_usuario = ($this->p61_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["p61_id_usuario"]:$this->p61_id_usuario);
       if($this->p61_dtandam == ""){
         $this->p61_dtandam_dia = ($this->p61_dtandam_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p61_dtandam_dia"]:$this->p61_dtandam_dia);
         $this->p61_dtandam_mes = ($this->p61_dtandam_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p61_dtandam_mes"]:$this->p61_dtandam_mes);
         $this->p61_dtandam_ano = ($this->p61_dtandam_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p61_dtandam_ano"]:$this->p61_dtandam_ano);
         if($this->p61_dtandam_dia != ""){
            $this->p61_dtandam = $this->p61_dtandam_ano."-".$this->p61_dtandam_mes."-".$this->p61_dtandam_dia;
         }
       }
       $this->p61_despacho = ($this->p61_despacho == ""?@$GLOBALS["HTTP_POST_VARS"]["p61_despacho"]:$this->p61_despacho);
       $this->p61_coddepto = ($this->p61_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["p61_coddepto"]:$this->p61_coddepto);
       $this->p61_publico = ($this->p61_publico == "f"?@$GLOBALS["HTTP_POST_VARS"]["p61_publico"]:$this->p61_publico);
       $this->p61_hora = ($this->p61_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["p61_hora"]:$this->p61_hora);
     }else{
       $this->p61_codandam = ($this->p61_codandam == ""?@$GLOBALS["HTTP_POST_VARS"]["p61_codandam"]:$this->p61_codandam);
     }
   }
   // funcao para inclusao
   function incluir ($p61_codandam){ 
      $this->atualizacampos();
     if($this->p61_codproc == null ){ 
       $this->erro_sql = " Campo Processo nao Informado.";
       $this->erro_campo = "p61_codproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p61_id_usuario == null ){ 
       $this->erro_sql = " Campo id do usuario nao Informado.";
       $this->erro_campo = "p61_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p61_dtandam == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "p61_dtandam_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p61_coddepto == null ){ 
       $this->erro_sql = " Campo departamento nao Informado.";
       $this->erro_campo = "p61_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p61_publico == null ){ 
       $this->erro_sql = " Campo Despacho Publico nao Informado.";
       $this->erro_campo = "p61_publico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p61_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "p61_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p61_codandam == "" || $p61_codandam == null ){
       $result = db_query("select nextval('procandam_p61_codandam_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: procandam_p61_codandam_seq do campo: p61_codandam"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p61_codandam = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from procandam_p61_codandam_seq");
       if(($result != false) && (pg_result($result,0,0) < $p61_codandam)){
         $this->erro_sql = " Campo p61_codandam maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p61_codandam = $p61_codandam; 
       }
     }
     if(($this->p61_codandam == null) || ($this->p61_codandam == "") ){ 
       $this->erro_sql = " Campo p61_codandam nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procandam(
                                       p61_codandam 
                                      ,p61_codproc 
                                      ,p61_id_usuario 
                                      ,p61_dtandam 
                                      ,p61_despacho 
                                      ,p61_coddepto 
                                      ,p61_publico 
                                      ,p61_hora 
                       )
                values (
                                $this->p61_codandam 
                               ,$this->p61_codproc 
                               ,$this->p61_id_usuario 
                               ,".($this->p61_dtandam == "null" || $this->p61_dtandam == ""?"null":"'".$this->p61_dtandam."'")." 
                               ,'$this->p61_despacho' 
                               ,$this->p61_coddepto 
                               ,'$this->p61_publico' 
                               ,'$this->p61_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->p61_codandam) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->p61_codandam) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p61_codandam;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p61_codandam));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2468,'$this->p61_codandam','I')");
       $resac = db_query("insert into db_acount values($acount,407,2468,'','".AddSlashes(pg_result($resaco,0,'p61_codandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,407,2469,'','".AddSlashes(pg_result($resaco,0,'p61_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,407,2470,'','".AddSlashes(pg_result($resaco,0,'p61_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,407,2471,'','".AddSlashes(pg_result($resaco,0,'p61_dtandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,407,2472,'','".AddSlashes(pg_result($resaco,0,'p61_despacho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,407,2473,'','".AddSlashes(pg_result($resaco,0,'p61_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,407,6521,'','".AddSlashes(pg_result($resaco,0,'p61_publico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,407,6561,'','".AddSlashes(pg_result($resaco,0,'p61_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p61_codandam=null) { 
      $this->atualizacampos();
     $sql = " update procandam set ";
     $virgula = "";
     if(trim($this->p61_codandam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p61_codandam"])){ 
       $sql  .= $virgula." p61_codandam = $this->p61_codandam ";
       $virgula = ",";
       if(trim($this->p61_codandam) == null ){ 
         $this->erro_sql = " Campo Código andamento nao Informado.";
         $this->erro_campo = "p61_codandam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p61_codproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p61_codproc"])){ 
       $sql  .= $virgula." p61_codproc = $this->p61_codproc ";
       $virgula = ",";
       if(trim($this->p61_codproc) == null ){ 
         $this->erro_sql = " Campo Processo nao Informado.";
         $this->erro_campo = "p61_codproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p61_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p61_id_usuario"])){ 
       $sql  .= $virgula." p61_id_usuario = $this->p61_id_usuario ";
       $virgula = ",";
       if(trim($this->p61_id_usuario) == null ){ 
         $this->erro_sql = " Campo id do usuario nao Informado.";
         $this->erro_campo = "p61_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p61_dtandam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p61_dtandam_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p61_dtandam_dia"] !="") ){ 
       $sql  .= $virgula." p61_dtandam = '$this->p61_dtandam' ";
       $virgula = ",";
       if(trim($this->p61_dtandam) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "p61_dtandam_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p61_dtandam_dia"])){ 
         $sql  .= $virgula." p61_dtandam = null ";
         $virgula = ",";
         if(trim($this->p61_dtandam) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "p61_dtandam_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->p61_despacho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p61_despacho"])){ 
       $sql  .= $virgula." p61_despacho = '$this->p61_despacho' ";
       $virgula = ",";
     }
     if(trim($this->p61_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p61_coddepto"])){ 
       $sql  .= $virgula." p61_coddepto = $this->p61_coddepto ";
       $virgula = ",";
       if(trim($this->p61_coddepto) == null ){ 
         $this->erro_sql = " Campo departamento nao Informado.";
         $this->erro_campo = "p61_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p61_publico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p61_publico"])){ 
       $sql  .= $virgula." p61_publico = '$this->p61_publico' ";
       $virgula = ",";
       if(trim($this->p61_publico) == null ){ 
         $this->erro_sql = " Campo Despacho Publico nao Informado.";
         $this->erro_campo = "p61_publico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p61_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p61_hora"])){ 
       $sql  .= $virgula." p61_hora = '$this->p61_hora' ";
       $virgula = ",";
       if(trim($this->p61_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "p61_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p61_codandam!=null){
       $sql .= " p61_codandam = $this->p61_codandam";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p61_codandam));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2468,'$this->p61_codandam','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p61_codandam"]))
           $resac = db_query("insert into db_acount values($acount,407,2468,'".AddSlashes(pg_result($resaco,$conresaco,'p61_codandam'))."','$this->p61_codandam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p61_codproc"]))
           $resac = db_query("insert into db_acount values($acount,407,2469,'".AddSlashes(pg_result($resaco,$conresaco,'p61_codproc'))."','$this->p61_codproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p61_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,407,2470,'".AddSlashes(pg_result($resaco,$conresaco,'p61_id_usuario'))."','$this->p61_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p61_dtandam"]))
           $resac = db_query("insert into db_acount values($acount,407,2471,'".AddSlashes(pg_result($resaco,$conresaco,'p61_dtandam'))."','$this->p61_dtandam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p61_despacho"]))
           $resac = db_query("insert into db_acount values($acount,407,2472,'".AddSlashes(pg_result($resaco,$conresaco,'p61_despacho'))."','$this->p61_despacho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p61_coddepto"]))
           $resac = db_query("insert into db_acount values($acount,407,2473,'".AddSlashes(pg_result($resaco,$conresaco,'p61_coddepto'))."','$this->p61_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p61_publico"]))
           $resac = db_query("insert into db_acount values($acount,407,6521,'".AddSlashes(pg_result($resaco,$conresaco,'p61_publico'))."','$this->p61_publico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p61_hora"]))
           $resac = db_query("insert into db_acount values($acount,407,6561,'".AddSlashes(pg_result($resaco,$conresaco,'p61_hora'))."','$this->p61_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p61_codandam;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p61_codandam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p61_codandam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p61_codandam=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p61_codandam));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2468,'$p61_codandam','E')");
         $resac = db_query("insert into db_acount values($acount,407,2468,'','".AddSlashes(pg_result($resaco,$iresaco,'p61_codandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,407,2469,'','".AddSlashes(pg_result($resaco,$iresaco,'p61_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,407,2470,'','".AddSlashes(pg_result($resaco,$iresaco,'p61_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,407,2471,'','".AddSlashes(pg_result($resaco,$iresaco,'p61_dtandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,407,2472,'','".AddSlashes(pg_result($resaco,$iresaco,'p61_despacho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,407,2473,'','".AddSlashes(pg_result($resaco,$iresaco,'p61_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,407,6521,'','".AddSlashes(pg_result($resaco,$iresaco,'p61_publico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,407,6561,'','".AddSlashes(pg_result($resaco,$iresaco,'p61_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from procandam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p61_codandam != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p61_codandam = $p61_codandam ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p61_codandam;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p61_codandam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p61_codandam;
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
        $this->erro_sql   = "Record Vazio na Tabela:procandam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $p61_codandam=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procandam ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = procandam.p61_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = procandam.p61_coddepto";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = procandam.p61_codproc";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  as a on   a.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($p61_codandam!=null ){
         $sql2 .= " where procandam.p61_codandam = $p61_codandam "; 
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
   function sql_query_com ( $p61_codandam=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from procandam ";
     $sql .= "      inner join db_usuarios  on  id_usuario = p61_id_usuario";
     $sql .= "      inner join db_depart   on  coddepto    = p61_coddepto";
     $sql .= "      inner join db_config   on  instit      = codigo";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = procandam.p61_codproc";
     $sql2 = "";
     if($dbwhere==""){
       if($p61_codandam!=null ){
         $sql2 .= " where procandam.p61_codandam = $p61_codandam ";
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
   function sql_query_file ( $p61_codandam=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procandam ";
     $sql2 = "";
     if($dbwhere==""){
       if($p61_codandam!=null ){
         $sql2 .= " where procandam.p61_codandam = $p61_codandam "; 
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