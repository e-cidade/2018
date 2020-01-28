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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhsolicita
class cl_rhsolicita { 
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
   var $rh33_sequencial = 0; 
   var $rh33_solicita = 0; 
   var $rh33_anousu = 0; 
   var $rh33_mesusu = 0; 
   var $rh33_siglaarq = null; 
   var $rh33_seqfolha = 0; 
   var $rh33_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh33_sequencial = int4 = Sequencial 
                 rh33_solicita = int4 = Solicitação 
                 rh33_anousu = int4 = Exercício 
                 rh33_mesusu = int4 = Mês 
                 rh33_siglaarq = varchar(3) = Sigla 
                 rh33_seqfolha = int4 = Seqüência da Folha 
                 rh33_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_rhsolicita() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhsolicita"); 
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
       $this->rh33_sequencial = ($this->rh33_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh33_sequencial"]:$this->rh33_sequencial);
       $this->rh33_solicita = ($this->rh33_solicita == ""?@$GLOBALS["HTTP_POST_VARS"]["rh33_solicita"]:$this->rh33_solicita);
       $this->rh33_anousu = ($this->rh33_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh33_anousu"]:$this->rh33_anousu);
       $this->rh33_mesusu = ($this->rh33_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh33_mesusu"]:$this->rh33_mesusu);
       $this->rh33_siglaarq = ($this->rh33_siglaarq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh33_siglaarq"]:$this->rh33_siglaarq);
       $this->rh33_seqfolha = ($this->rh33_seqfolha == ""?@$GLOBALS["HTTP_POST_VARS"]["rh33_seqfolha"]:$this->rh33_seqfolha);
       $this->rh33_instit = ($this->rh33_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh33_instit"]:$this->rh33_instit);
     }else{
       $this->rh33_sequencial = ($this->rh33_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh33_sequencial"]:$this->rh33_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh33_sequencial){ 
      $this->atualizacampos();
     if($this->rh33_solicita == null ){ 
       $this->erro_sql = " Campo Solicitação nao Informado.";
       $this->erro_campo = "rh33_solicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh33_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "rh33_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh33_mesusu == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "rh33_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh33_siglaarq == null ){ 
       $this->erro_sql = " Campo Sigla nao Informado.";
       $this->erro_campo = "rh33_siglaarq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh33_seqfolha == null ){ 
       $this->erro_sql = " Campo Seqüência da Folha nao Informado.";
       $this->erro_campo = "rh33_seqfolha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh33_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "rh33_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh33_sequencial == "" || $rh33_sequencial == null ){
       $result = db_query("select nextval('rhsolicita_rh33_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhsolicita_rh33_sequencial_seq do campo: rh33_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh33_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhsolicita_rh33_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh33_sequencial)){
         $this->erro_sql = " Campo rh33_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh33_sequencial = $rh33_sequencial; 
       }
     }
     if(($this->rh33_sequencial == null) || ($this->rh33_sequencial == "") ){ 
       $this->erro_sql = " Campo rh33_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhsolicita(
                                       rh33_sequencial 
                                      ,rh33_solicita 
                                      ,rh33_anousu 
                                      ,rh33_mesusu 
                                      ,rh33_siglaarq 
                                      ,rh33_seqfolha 
                                      ,rh33_instit 
                       )
                values (
                                $this->rh33_sequencial 
                               ,$this->rh33_solicita 
                               ,$this->rh33_anousu 
                               ,$this->rh33_mesusu 
                               ,'$this->rh33_siglaarq' 
                               ,$this->rh33_seqfolha 
                               ,$this->rh33_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Solicitação de compras da folha ($this->rh33_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Solicitação de compras da folha já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Solicitação de compras da folha ($this->rh33_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh33_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh33_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10743,'$this->rh33_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1850,10743,'','".AddSlashes(pg_result($resaco,0,'rh33_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1850,10744,'','".AddSlashes(pg_result($resaco,0,'rh33_solicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1850,10745,'','".AddSlashes(pg_result($resaco,0,'rh33_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1850,10746,'','".AddSlashes(pg_result($resaco,0,'rh33_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1850,10747,'','".AddSlashes(pg_result($resaco,0,'rh33_siglaarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1850,10748,'','".AddSlashes(pg_result($resaco,0,'rh33_seqfolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1850,10749,'','".AddSlashes(pg_result($resaco,0,'rh33_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh33_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhsolicita set ";
     $virgula = "";
     if(trim($this->rh33_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh33_sequencial"])){ 
       $sql  .= $virgula." rh33_sequencial = $this->rh33_sequencial ";
       $virgula = ",";
       if(trim($this->rh33_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh33_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh33_solicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh33_solicita"])){ 
       $sql  .= $virgula." rh33_solicita = $this->rh33_solicita ";
       $virgula = ",";
       if(trim($this->rh33_solicita) == null ){ 
         $this->erro_sql = " Campo Solicitação nao Informado.";
         $this->erro_campo = "rh33_solicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh33_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh33_anousu"])){ 
       $sql  .= $virgula." rh33_anousu = $this->rh33_anousu ";
       $virgula = ",";
       if(trim($this->rh33_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "rh33_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh33_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh33_mesusu"])){ 
       $sql  .= $virgula." rh33_mesusu = $this->rh33_mesusu ";
       $virgula = ",";
       if(trim($this->rh33_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "rh33_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh33_siglaarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh33_siglaarq"])){ 
       $sql  .= $virgula." rh33_siglaarq = '$this->rh33_siglaarq' ";
       $virgula = ",";
       if(trim($this->rh33_siglaarq) == null ){ 
         $this->erro_sql = " Campo Sigla nao Informado.";
         $this->erro_campo = "rh33_siglaarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh33_seqfolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh33_seqfolha"])){ 
       $sql  .= $virgula." rh33_seqfolha = $this->rh33_seqfolha ";
       $virgula = ",";
       if(trim($this->rh33_seqfolha) == null ){ 
         $this->erro_sql = " Campo Seqüência da Folha nao Informado.";
         $this->erro_campo = "rh33_seqfolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh33_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh33_instit"])){ 
       $sql  .= $virgula." rh33_instit = $this->rh33_instit ";
       $virgula = ",";
       if(trim($this->rh33_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "rh33_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh33_sequencial!=null){
       $sql .= " rh33_sequencial = $this->rh33_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh33_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10743,'$this->rh33_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh33_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1850,10743,'".AddSlashes(pg_result($resaco,$conresaco,'rh33_sequencial'))."','$this->rh33_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh33_solicita"]))
           $resac = db_query("insert into db_acount values($acount,1850,10744,'".AddSlashes(pg_result($resaco,$conresaco,'rh33_solicita'))."','$this->rh33_solicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh33_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1850,10745,'".AddSlashes(pg_result($resaco,$conresaco,'rh33_anousu'))."','$this->rh33_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh33_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,1850,10746,'".AddSlashes(pg_result($resaco,$conresaco,'rh33_mesusu'))."','$this->rh33_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh33_siglaarq"]))
           $resac = db_query("insert into db_acount values($acount,1850,10747,'".AddSlashes(pg_result($resaco,$conresaco,'rh33_siglaarq'))."','$this->rh33_siglaarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh33_seqfolha"]))
           $resac = db_query("insert into db_acount values($acount,1850,10748,'".AddSlashes(pg_result($resaco,$conresaco,'rh33_seqfolha'))."','$this->rh33_seqfolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh33_instit"]))
           $resac = db_query("insert into db_acount values($acount,1850,10749,'".AddSlashes(pg_result($resaco,$conresaco,'rh33_instit'))."','$this->rh33_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Solicitação de compras da folha nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh33_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Solicitação de compras da folha nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh33_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh33_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10743,'$rh33_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1850,10743,'','".AddSlashes(pg_result($resaco,$iresaco,'rh33_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1850,10744,'','".AddSlashes(pg_result($resaco,$iresaco,'rh33_solicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1850,10745,'','".AddSlashes(pg_result($resaco,$iresaco,'rh33_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1850,10746,'','".AddSlashes(pg_result($resaco,$iresaco,'rh33_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1850,10747,'','".AddSlashes(pg_result($resaco,$iresaco,'rh33_siglaarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1850,10748,'','".AddSlashes(pg_result($resaco,$iresaco,'rh33_seqfolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1850,10749,'','".AddSlashes(pg_result($resaco,$iresaco,'rh33_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhsolicita
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh33_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh33_sequencial = $rh33_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Solicitação de compras da folha nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh33_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Solicitação de compras da folha nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh33_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhsolicita";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh33_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhsolicita ";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = rhsolicita.rh33_solicita";
     $sql .= "      inner join db_config  on  db_config.codigo = solicita.pc10_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = solicita.pc10_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($rh33_sequencial!=null ){
         $sql2 .= " where rhsolicita.rh33_sequencial = $rh33_sequencial "; 
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
   function sql_query_file ( $rh33_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhsolicita ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh33_sequencial!=null ){
         $sql2 .= " where rhsolicita.rh33_sequencial = $rh33_sequencial "; 
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
   function sql_query_pcproc ( $rh33_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhsolicita ";
     $sql .= "      inner join solicita    on solicita.pc10_numero      = rhsolicita.rh33_solicita";
     $sql .= "      inner join solicitem   on solicitem.pc11_numero     = solicita.pc10_numero";
     $sql .= "      inner join db_config   on db_config.codigo          = solicita.pc10_instit";
     $sql .= "      inner join db_usuarios on db_usuarios.id_usuario    = solicita.pc10_login";
     $sql .= "      inner join db_depart   on db_depart.coddepto        = solicita.pc10_depto";
     $sql .= "      left  join pcprocitem  on pcprocitem.pc81_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcproc      on pcproc.pc80_codproc       = pcprocitem.pc81_codproc";
     $sql2 = "";
     if($dbwhere==""){
       if($rh33_sequencial!=null ){
         $sql2 .= " where rhsolicita.rh33_sequencial = $rh33_sequencial "; 
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