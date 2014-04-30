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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE rhestagio
class cl_rhestagio { 
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
   var $h50_sequencial = 0; 
   var $h50_lei = null; 
   var $h50_descr = null; 
   var $h50_obs = null; 
   var $h50_confobs = 0; 
   var $h50_minimopontos = 0; 
   var $h50_assentaaprova = 0; 
   var $h50_assentareprova = 0; 
   var $h50_duracaoestagio = 0; 
   var $h50_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h50_sequencial = int4 = Sequencial 
                 h50_lei = varchar(20) = Lei 
                 h50_descr = varchar(40) = Descrição 
                 h50_obs = text = Observação 
                 h50_confobs = int4 = Confobs 
                 h50_minimopontos = int4 = Mínino de pontos 
                 h50_assentaaprova = int4 = Portaria Aprovação 
                 h50_assentareprova = int4 = Portaria Reprovação 
                 h50_duracaoestagio = int4 = Duração do Estágio 
                 h50_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_rhestagio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhestagio"); 
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
       $this->h50_sequencial = ($this->h50_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h50_sequencial"]:$this->h50_sequencial);
       $this->h50_lei = ($this->h50_lei == ""?@$GLOBALS["HTTP_POST_VARS"]["h50_lei"]:$this->h50_lei);
       $this->h50_descr = ($this->h50_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["h50_descr"]:$this->h50_descr);
       $this->h50_obs = ($this->h50_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["h50_obs"]:$this->h50_obs);
       $this->h50_confobs = ($this->h50_confobs == ""?@$GLOBALS["HTTP_POST_VARS"]["h50_confobs"]:$this->h50_confobs);
       $this->h50_minimopontos = ($this->h50_minimopontos == ""?@$GLOBALS["HTTP_POST_VARS"]["h50_minimopontos"]:$this->h50_minimopontos);
       $this->h50_assentaaprova = ($this->h50_assentaaprova == ""?@$GLOBALS["HTTP_POST_VARS"]["h50_assentaaprova"]:$this->h50_assentaaprova);
       $this->h50_assentareprova = ($this->h50_assentareprova == ""?@$GLOBALS["HTTP_POST_VARS"]["h50_assentareprova"]:$this->h50_assentareprova);
       $this->h50_duracaoestagio = ($this->h50_duracaoestagio == ""?@$GLOBALS["HTTP_POST_VARS"]["h50_duracaoestagio"]:$this->h50_duracaoestagio);
       $this->h50_instit = ($this->h50_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["h50_instit"]:$this->h50_instit);
     }else{
       $this->h50_sequencial = ($this->h50_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h50_sequencial"]:$this->h50_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h50_sequencial){ 
      $this->atualizacampos();
     if($this->h50_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "h50_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h50_confobs == null ){ 
       $this->h50_confobs = "0";
     }
     if($this->h50_minimopontos == null ){ 
       $this->erro_sql = " Campo Mínino de pontos nao Informado.";
       $this->erro_campo = "h50_minimopontos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h50_assentaaprova == null ){ 
       $this->erro_sql = " Campo Portaria Aprovação nao Informado.";
       $this->erro_campo = "h50_assentaaprova";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h50_assentareprova == null ){ 
       $this->erro_sql = " Campo Portaria Reprovação nao Informado.";
       $this->erro_campo = "h50_assentareprova";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h50_duracaoestagio == null ){ 
       $this->erro_sql = " Campo Duração do Estágio nao Informado.";
       $this->erro_campo = "h50_duracaoestagio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h50_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "h50_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h50_sequencial == "" || $h50_sequencial == null ){
       $result = db_query("select nextval('rhestagio_h50_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhestagio_h50_sequencial_seq do campo: h50_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h50_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhestagio_h50_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h50_sequencial)){
         $this->erro_sql = " Campo h50_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h50_sequencial = $h50_sequencial; 
       }
     }
     if(($this->h50_sequencial == null) || ($this->h50_sequencial == "") ){ 
       $this->erro_sql = " Campo h50_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhestagio(
                                       h50_sequencial 
                                      ,h50_lei 
                                      ,h50_descr 
                                      ,h50_obs 
                                      ,h50_confobs 
                                      ,h50_minimopontos 
                                      ,h50_assentaaprova 
                                      ,h50_assentareprova 
                                      ,h50_duracaoestagio 
                                      ,h50_instit 
                       )
                values (
                                $this->h50_sequencial 
                               ,'$this->h50_lei' 
                               ,'$this->h50_descr' 
                               ,'$this->h50_obs' 
                               ,$this->h50_confobs 
                               ,$this->h50_minimopontos 
                               ,$this->h50_assentaaprova 
                               ,$this->h50_assentareprova 
                               ,$this->h50_duracaoestagio 
                               ,$this->h50_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhestagio ($this->h50_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhestagio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhestagio ($this->h50_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h50_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h50_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10849,'$this->h50_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1868,10849,'','".AddSlashes(pg_result($resaco,0,'h50_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1868,10850,'','".AddSlashes(pg_result($resaco,0,'h50_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1868,10851,'','".AddSlashes(pg_result($resaco,0,'h50_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1868,10852,'','".AddSlashes(pg_result($resaco,0,'h50_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1868,10853,'','".AddSlashes(pg_result($resaco,0,'h50_confobs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1868,10942,'','".AddSlashes(pg_result($resaco,0,'h50_minimopontos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1868,10943,'','".AddSlashes(pg_result($resaco,0,'h50_assentaaprova'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1868,10944,'','".AddSlashes(pg_result($resaco,0,'h50_assentareprova'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1868,10945,'','".AddSlashes(pg_result($resaco,0,'h50_duracaoestagio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1868,10946,'','".AddSlashes(pg_result($resaco,0,'h50_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h50_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhestagio set ";
     $virgula = "";
     if(trim($this->h50_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h50_sequencial"])){ 
       $sql  .= $virgula." h50_sequencial = $this->h50_sequencial ";
       $virgula = ",";
       if(trim($this->h50_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "h50_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h50_lei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h50_lei"])){ 
       $sql  .= $virgula." h50_lei = '$this->h50_lei' ";
       $virgula = ",";
     }
     if(trim($this->h50_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h50_descr"])){ 
       $sql  .= $virgula." h50_descr = '$this->h50_descr' ";
       $virgula = ",";
       if(trim($this->h50_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "h50_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h50_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h50_obs"])){ 
       $sql  .= $virgula." h50_obs = '$this->h50_obs' ";
       $virgula = ",";
     }
     if(trim($this->h50_confobs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h50_confobs"])){ 
        if(trim($this->h50_confobs)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h50_confobs"])){ 
           $this->h50_confobs = "0" ; 
        } 
       $sql  .= $virgula." h50_confobs = $this->h50_confobs ";
       $virgula = ",";
     }
     if(trim($this->h50_minimopontos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h50_minimopontos"])){ 
       $sql  .= $virgula." h50_minimopontos = $this->h50_minimopontos ";
       $virgula = ",";
       if(trim($this->h50_minimopontos) == null ){ 
         $this->erro_sql = " Campo Mínino de pontos nao Informado.";
         $this->erro_campo = "h50_minimopontos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h50_assentaaprova)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h50_assentaaprova"])){ 
       $sql  .= $virgula." h50_assentaaprova = $this->h50_assentaaprova ";
       $virgula = ",";
       if(trim($this->h50_assentaaprova) == null ){ 
         $this->erro_sql = " Campo Portaria Aprovação nao Informado.";
         $this->erro_campo = "h50_assentaaprova";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h50_assentareprova)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h50_assentareprova"])){ 
       $sql  .= $virgula." h50_assentareprova = $this->h50_assentareprova ";
       $virgula = ",";
       if(trim($this->h50_assentareprova) == null ){ 
         $this->erro_sql = " Campo Portaria Reprovação nao Informado.";
         $this->erro_campo = "h50_assentareprova";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h50_duracaoestagio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h50_duracaoestagio"])){ 
       $sql  .= $virgula." h50_duracaoestagio = $this->h50_duracaoestagio ";
       $virgula = ",";
       if(trim($this->h50_duracaoestagio) == null ){ 
         $this->erro_sql = " Campo Duração do Estágio nao Informado.";
         $this->erro_campo = "h50_duracaoestagio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h50_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h50_instit"])){ 
       $sql  .= $virgula." h50_instit = $this->h50_instit ";
       $virgula = ",";
       if(trim($this->h50_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "h50_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h50_sequencial!=null){
       $sql .= " h50_sequencial = $this->h50_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h50_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10849,'$this->h50_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h50_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1868,10849,'".AddSlashes(pg_result($resaco,$conresaco,'h50_sequencial'))."','$this->h50_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h50_lei"]))
           $resac = db_query("insert into db_acount values($acount,1868,10850,'".AddSlashes(pg_result($resaco,$conresaco,'h50_lei'))."','$this->h50_lei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h50_descr"]))
           $resac = db_query("insert into db_acount values($acount,1868,10851,'".AddSlashes(pg_result($resaco,$conresaco,'h50_descr'))."','$this->h50_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h50_obs"]))
           $resac = db_query("insert into db_acount values($acount,1868,10852,'".AddSlashes(pg_result($resaco,$conresaco,'h50_obs'))."','$this->h50_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h50_confobs"]))
           $resac = db_query("insert into db_acount values($acount,1868,10853,'".AddSlashes(pg_result($resaco,$conresaco,'h50_confobs'))."','$this->h50_confobs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h50_minimopontos"]))
           $resac = db_query("insert into db_acount values($acount,1868,10942,'".AddSlashes(pg_result($resaco,$conresaco,'h50_minimopontos'))."','$this->h50_minimopontos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h50_assentaaprova"]))
           $resac = db_query("insert into db_acount values($acount,1868,10943,'".AddSlashes(pg_result($resaco,$conresaco,'h50_assentaaprova'))."','$this->h50_assentaaprova',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h50_assentareprova"]))
           $resac = db_query("insert into db_acount values($acount,1868,10944,'".AddSlashes(pg_result($resaco,$conresaco,'h50_assentareprova'))."','$this->h50_assentareprova',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h50_duracaoestagio"]))
           $resac = db_query("insert into db_acount values($acount,1868,10945,'".AddSlashes(pg_result($resaco,$conresaco,'h50_duracaoestagio'))."','$this->h50_duracaoestagio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h50_instit"]))
           $resac = db_query("insert into db_acount values($acount,1868,10946,'".AddSlashes(pg_result($resaco,$conresaco,'h50_instit'))."','$this->h50_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhestagio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h50_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhestagio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h50_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h50_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h50_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h50_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10849,'$h50_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1868,10849,'','".AddSlashes(pg_result($resaco,$iresaco,'h50_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1868,10850,'','".AddSlashes(pg_result($resaco,$iresaco,'h50_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1868,10851,'','".AddSlashes(pg_result($resaco,$iresaco,'h50_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1868,10852,'','".AddSlashes(pg_result($resaco,$iresaco,'h50_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1868,10853,'','".AddSlashes(pg_result($resaco,$iresaco,'h50_confobs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1868,10942,'','".AddSlashes(pg_result($resaco,$iresaco,'h50_minimopontos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1868,10943,'','".AddSlashes(pg_result($resaco,$iresaco,'h50_assentaaprova'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1868,10944,'','".AddSlashes(pg_result($resaco,$iresaco,'h50_assentareprova'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1868,10945,'','".AddSlashes(pg_result($resaco,$iresaco,'h50_duracaoestagio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1868,10946,'','".AddSlashes(pg_result($resaco,$iresaco,'h50_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhestagio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h50_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h50_sequencial = $h50_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhestagio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h50_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhestagio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h50_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h50_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhestagio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h50_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagio ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhestagio.h50_instit";
     $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = rhestagio.h50_assentaaprova";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($h50_sequencial!=null ){
         $sql2 .= " where rhestagio.h50_sequencial = $h50_sequencial "; 
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
   function sql_query_file ( $h50_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagio ";
     $sql2 = "";
     if($dbwhere==""){
       if($h50_sequencial!=null ){
         $sql2 .= " where rhestagio.h50_sequencial = $h50_sequencial "; 
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
   function sql_query_assenta( $h50_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from rhestagio ";
     $sql .= "      inner join tipoasse aprova  on  aprova.h12_codigo = rhestagio.h50_assentaaprova";
     $sql .= "      inner join tipoasse reprova on  reprova.h12_codigo = rhestagio.h50_assentareprova";
     $sql2 = "";
     if($dbwhere==""){
       if($h50_sequencial!=null ){
         $sql2 .= " where rhestagio.h50_sequencial = $h50_sequencial ";
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