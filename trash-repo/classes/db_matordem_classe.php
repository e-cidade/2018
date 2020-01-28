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

//MODULO: material
//CLASSE DA ENTIDADE matordem
class cl_matordem { 
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
   var $m51_codordem = 0; 
   var $m51_data_dia = null; 
   var $m51_data_mes = null; 
   var $m51_data_ano = null; 
   var $m51_data = null; 
   var $m51_depto = 0; 
   var $m51_numcgm = 0; 
   var $m51_obs = null; 
   var $m51_valortotal = 0; 
   var $m51_prazoent = 0; 
   var $m51_tipo = 0; 
   var $m51_deptoorigem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m51_codordem = int8 = Código 
                 m51_data = date = Data emissão 
                 m51_depto = int8 = Departamento 
                 m51_numcgm = int4 = Fornecedor 
                 m51_obs = text = Observações 
                 m51_valortotal = float8 = Valor Total 
                 m51_prazoent = int8 = Dias de prazo para entrega 
                 m51_tipo = int4 = Tipo da Ordem 
                 m51_deptoorigem = int4 = Departamento de Origem 
                 ";
   //funcao construtor da classe 
   function cl_matordem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matordem"); 
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
       $this->m51_codordem = ($this->m51_codordem == ""?@$GLOBALS["HTTP_POST_VARS"]["m51_codordem"]:$this->m51_codordem);
       if($this->m51_data == ""){
         $this->m51_data_dia = ($this->m51_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m51_data_dia"]:$this->m51_data_dia);
         $this->m51_data_mes = ($this->m51_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m51_data_mes"]:$this->m51_data_mes);
         $this->m51_data_ano = ($this->m51_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m51_data_ano"]:$this->m51_data_ano);
         if($this->m51_data_dia != ""){
            $this->m51_data = $this->m51_data_ano."-".$this->m51_data_mes."-".$this->m51_data_dia;
         }
       }
       $this->m51_depto = ($this->m51_depto == ""?@$GLOBALS["HTTP_POST_VARS"]["m51_depto"]:$this->m51_depto);
       $this->m51_numcgm = ($this->m51_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["m51_numcgm"]:$this->m51_numcgm);
       $this->m51_obs = ($this->m51_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["m51_obs"]:$this->m51_obs);
       $this->m51_valortotal = ($this->m51_valortotal == ""?@$GLOBALS["HTTP_POST_VARS"]["m51_valortotal"]:$this->m51_valortotal);
       $this->m51_prazoent = ($this->m51_prazoent == ""?@$GLOBALS["HTTP_POST_VARS"]["m51_prazoent"]:$this->m51_prazoent);
       $this->m51_tipo = ($this->m51_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["m51_tipo"]:$this->m51_tipo);
       $this->m51_deptoorigem = ($this->m51_deptoorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["m51_deptoorigem"]:$this->m51_deptoorigem);
     }else{
       $this->m51_codordem = ($this->m51_codordem == ""?@$GLOBALS["HTTP_POST_VARS"]["m51_codordem"]:$this->m51_codordem);
     }
   }
   // funcao para inclusao
   function incluir ($m51_codordem){ 
      $this->atualizacampos();
     if($this->m51_data == null ){ 
       $this->erro_sql = " Campo Data emissão nao Informado.";
       $this->erro_campo = "m51_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m51_depto == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "m51_depto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m51_numcgm == null ){ 
       $this->erro_sql = " Campo Fornecedor nao Informado.";
       $this->erro_campo = "m51_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m51_valortotal == null ){ 
       $this->erro_sql = " Campo Valor Total nao Informado.";
       $this->erro_campo = "m51_valortotal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m51_prazoent == null ){ 
       $this->erro_sql = " Campo Dias de prazo para entrega nao Informado.";
       $this->erro_campo = "m51_prazoent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m51_tipo == null ){ 
       $this->erro_sql = " Campo Tipo da Ordem nao Informado.";
       $this->erro_campo = "m51_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m51_deptoorigem == null ){ 
       $this->m51_deptoorigem = "0";
     }
     if($m51_codordem == "" || $m51_codordem == null ){
       $result = db_query("select nextval('matordem_m51_codordem_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matordem_m51_codordem_seq do campo: m51_codordem"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m51_codordem = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matordem_m51_codordem_seq");
       if(($result != false) && (pg_result($result,0,0) < $m51_codordem)){
         $this->erro_sql = " Campo m51_codordem maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m51_codordem = $m51_codordem; 
       }
     }
     if(($this->m51_codordem == null) || ($this->m51_codordem == "") ){ 
       $this->erro_sql = " Campo m51_codordem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->m51_deptoorigem = db_getsession("DB_coddepto");
     $sql = "insert into matordem(
                                       m51_codordem 
                                      ,m51_data 
                                      ,m51_depto 
                                      ,m51_numcgm 
                                      ,m51_obs 
                                      ,m51_valortotal 
                                      ,m51_prazoent 
                                      ,m51_tipo 
                                      ,m51_deptoorigem 
                       )
                values (
                                $this->m51_codordem 
                               ,".($this->m51_data == "null" || $this->m51_data == ""?"null":"'".$this->m51_data."'")." 
                               ,$this->m51_depto 
                               ,$this->m51_numcgm 
                               ,'$this->m51_obs' 
                               ,$this->m51_valortotal 
                               ,$this->m51_prazoent 
                               ,$this->m51_tipo 
                               ,$this->m51_deptoorigem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ordem de compra ($this->m51_codordem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ordem de compra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ordem de compra ($this->m51_codordem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m51_codordem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m51_codordem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6216,'$this->m51_codordem','I')");
       $resac = db_query("insert into db_acount values($acount,1007,6216,'','".AddSlashes(pg_result($resaco,0,'m51_codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1007,6217,'','".AddSlashes(pg_result($resaco,0,'m51_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1007,6218,'','".AddSlashes(pg_result($resaco,0,'m51_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1007,6227,'','".AddSlashes(pg_result($resaco,0,'m51_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1007,6252,'','".AddSlashes(pg_result($resaco,0,'m51_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1007,6257,'','".AddSlashes(pg_result($resaco,0,'m51_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1007,6601,'','".AddSlashes(pg_result($resaco,0,'m51_prazoent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1007,10840,'','".AddSlashes(pg_result($resaco,0,'m51_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1007,19240,'','".AddSlashes(pg_result($resaco,0,'m51_deptoorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m51_codordem=null) { 
     
     $this->m51_deptoorigem = db_getsession("DB_coddepto");
     $this->atualizacampos();
     $sql = " update matordem set ";
     $virgula = "";
     if(trim($this->m51_codordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m51_codordem"])){ 
       $sql  .= $virgula." m51_codordem = $this->m51_codordem ";
       $virgula = ",";
       if(trim($this->m51_codordem) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "m51_codordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m51_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m51_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m51_data_dia"] !="") ){ 
       $sql  .= $virgula." m51_data = '$this->m51_data' ";
       $virgula = ",";
       if(trim($this->m51_data) == null ){ 
         $this->erro_sql = " Campo Data emissão nao Informado.";
         $this->erro_campo = "m51_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m51_data_dia"])){ 
         $sql  .= $virgula." m51_data = null ";
         $virgula = ",";
         if(trim($this->m51_data) == null ){ 
           $this->erro_sql = " Campo Data emissão nao Informado.";
           $this->erro_campo = "m51_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->m51_depto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m51_depto"])){ 
       $sql  .= $virgula." m51_depto = $this->m51_depto ";
       $virgula = ",";
       if(trim($this->m51_depto) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "m51_depto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m51_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m51_numcgm"])){ 
       $sql  .= $virgula." m51_numcgm = $this->m51_numcgm ";
       $virgula = ",";
       if(trim($this->m51_numcgm) == null ){ 
         $this->erro_sql = " Campo Fornecedor nao Informado.";
         $this->erro_campo = "m51_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m51_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m51_obs"])){ 
       $sql  .= $virgula." m51_obs = '$this->m51_obs' ";
       $virgula = ",";
     }
     if(trim($this->m51_valortotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m51_valortotal"])){ 
       $sql  .= $virgula." m51_valortotal = $this->m51_valortotal ";
       $virgula = ",";
       if(trim($this->m51_valortotal) == null ){ 
         $this->erro_sql = " Campo Valor Total nao Informado.";
         $this->erro_campo = "m51_valortotal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m51_prazoent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m51_prazoent"])){ 
       $sql  .= $virgula." m51_prazoent = $this->m51_prazoent ";
       $virgula = ",";
       if(trim($this->m51_prazoent) == null ){ 
         $this->erro_sql = " Campo Dias de prazo para entrega nao Informado.";
         $this->erro_campo = "m51_prazoent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m51_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m51_tipo"])){ 
       $sql  .= $virgula." m51_tipo = $this->m51_tipo ";
       $virgula = ",";
       if(trim($this->m51_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo da Ordem nao Informado.";
         $this->erro_campo = "m51_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m51_deptoorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m51_deptoorigem"])){ 
        if(trim($this->m51_deptoorigem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["m51_deptoorigem"])){ 
           $this->m51_deptoorigem = "0" ; 
        } 
       $sql  .= $virgula." m51_deptoorigem = $this->m51_deptoorigem ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($m51_codordem!=null){
       $sql .= " m51_codordem = $this->m51_codordem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m51_codordem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6216,'$this->m51_codordem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m51_codordem"]) || $this->m51_codordem != "")
           $resac = db_query("insert into db_acount values($acount,1007,6216,'".AddSlashes(pg_result($resaco,$conresaco,'m51_codordem'))."','$this->m51_codordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m51_data"]) || $this->m51_data != "")
           $resac = db_query("insert into db_acount values($acount,1007,6217,'".AddSlashes(pg_result($resaco,$conresaco,'m51_data'))."','$this->m51_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m51_depto"]) || $this->m51_depto != "")
           $resac = db_query("insert into db_acount values($acount,1007,6218,'".AddSlashes(pg_result($resaco,$conresaco,'m51_depto'))."','$this->m51_depto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m51_numcgm"]) || $this->m51_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,1007,6227,'".AddSlashes(pg_result($resaco,$conresaco,'m51_numcgm'))."','$this->m51_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m51_obs"]) || $this->m51_obs != "")
           $resac = db_query("insert into db_acount values($acount,1007,6252,'".AddSlashes(pg_result($resaco,$conresaco,'m51_obs'))."','$this->m51_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m51_valortotal"]) || $this->m51_valortotal != "")
           $resac = db_query("insert into db_acount values($acount,1007,6257,'".AddSlashes(pg_result($resaco,$conresaco,'m51_valortotal'))."','$this->m51_valortotal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m51_prazoent"]) || $this->m51_prazoent != "")
           $resac = db_query("insert into db_acount values($acount,1007,6601,'".AddSlashes(pg_result($resaco,$conresaco,'m51_prazoent'))."','$this->m51_prazoent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m51_tipo"]) || $this->m51_tipo != "")
           $resac = db_query("insert into db_acount values($acount,1007,10840,'".AddSlashes(pg_result($resaco,$conresaco,'m51_tipo'))."','$this->m51_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m51_deptoorigem"]) || $this->m51_deptoorigem != "")
           $resac = db_query("insert into db_acount values($acount,1007,19240,'".AddSlashes(pg_result($resaco,$conresaco,'m51_deptoorigem'))."','$this->m51_deptoorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordem de compra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m51_codordem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordem de compra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m51_codordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m51_codordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m51_codordem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m51_codordem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6216,'$m51_codordem','E')");
         $resac = db_query("insert into db_acount values($acount,1007,6216,'','".AddSlashes(pg_result($resaco,$iresaco,'m51_codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1007,6217,'','".AddSlashes(pg_result($resaco,$iresaco,'m51_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1007,6218,'','".AddSlashes(pg_result($resaco,$iresaco,'m51_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1007,6227,'','".AddSlashes(pg_result($resaco,$iresaco,'m51_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1007,6252,'','".AddSlashes(pg_result($resaco,$iresaco,'m51_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1007,6257,'','".AddSlashes(pg_result($resaco,$iresaco,'m51_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1007,6601,'','".AddSlashes(pg_result($resaco,$iresaco,'m51_prazoent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1007,10840,'','".AddSlashes(pg_result($resaco,$iresaco,'m51_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1007,19240,'','".AddSlashes(pg_result($resaco,$iresaco,'m51_deptoorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matordem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m51_codordem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m51_codordem = $m51_codordem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordem de compra nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m51_codordem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordem de compra nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m51_codordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m51_codordem;
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
        $this->erro_sql   = "Record Vazio na Tabela:matordem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m51_codordem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordem                                                                                       ";
     $sql .= "  inner join cgm on cgm.z01_numcgm = matordem.m51_numcgm                                             ";  
     $sql .= "    inner join db_depart on db_depart.coddepto = matordem.m51_depto                                  ";
     $sql .= "    inner join db_depart as db_depart_origem on db_depart_origem.coddepto = matordem.m51_deptoorigem ";
     $sql2 = "    inner join db_config on db_config.codigo = db_depart.instit                                      ";
     if($dbwhere==""){
       if($m51_codordem!=null ){
         $sql2 .= " where matordem.m51_codordem = $m51_codordem "; 
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
   function sql_query_file ( $m51_codordem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordem ";
     $sql2 = "";
     if($dbwhere==""){
       if($m51_codordem!=null ){
         $sql2 .= " where matordem.m51_codordem = $m51_codordem "; 
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
   function sql_query_anu( $m51_codordem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matordem ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = matordem.m51_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matordem.m51_depto";
     $sql .= "      inner join matordemitem on matordemitem.m52_codordem = matordem.m51_codordem";
     $sql .= "      inner join empempenho on empempenho.e60_numemp = matordemitem.m52_numemp";
     $sql .= "      left join matordemanu  on  matordemanu.m53_codordem = matordem.m51_codordem";
     $sql2 = "";
     if($dbwhere==""){
       if($m51_codordem!=null ){
         $sql2 .= " where matordem.m51_codordem = $m51_codordem ";
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
   function sql_query_infoemp ( $m51_codordem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matordem ";
     $sql .= "      inner join matordemitem  on matordemitem.m52_codordem = matordem.m51_codordem";
     $sql .= "      inner join empempitem  	on empempitem.e62_numemp = matordemitem.m52_numemp and  empempitem.e62_sequen = matordemitem.m52_sequen";    
     $sql .= "      inner join pcmater  	on pcmater.pc01_codmater = empempitem.e62_item";
     $sql .= "      inner join pcsubgrupo   on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      inner join pcgrupo      on  pcgrupo.pc03_codgrupo = pcsubgrupo.pc04_codgrupo";
     $sql .= "      inner join empempenho  	on empempenho.e60_numemp = empempitem.e62_numemp";
     $sql .= "      inner join empempaut	on empempaut.e61_numemp = empempenho.e60_numemp";
     $sql .= "      inner join empautoriza 	on empempaut.e61_autori = empautoriza.e54_autori ";
     $sql .= "      inner join cgm  		on cgm.z01_numcgm = matordem.m51_numcgm";
     $sql .= "      inner join db_depart  	on db_depart.coddepto = matordem.m51_depto";
     $sql .= "      inner join orcdotacao   on o58_coddot=e60_coddot";     
	 $sql .= "		inner join orcorgao     on o40_anousu 	 = ".db_getsession("DB_anousu")." and o40_orgao = o58_orgao";
	 $sql .= "		inner join orcunidade   on o41_anousu 	 = ".db_getsession("DB_anousu")." and o41_orgao = o58_orgao and o41_unidade= o58_unidade";
	 $sql .= "		inner join orcfuncao    on o52_funcao 	 = o58_funcao";
	 $sql .= "		inner join orcsubfuncao on o53_subfuncao = o58_subfuncao";
	 $sql .= "		inner join orcprograma  on o54_anousu 	 = ".db_getsession("DB_anousu")." and o54_programa = o58_programa";
	 $sql .= "		inner join orcprojativ  on o55_anousu 	 = ".db_getsession("DB_anousu")." and o55_projativ = o58_projativ";
	 $sql .= "		inner join orcelemento  on o56_codele    = o58_codele and o56_anousu = ".db_getsession("DB_anousu");
 	 $sql .= " 		inner join orctiporec   on o15_codigo    = o58_codigo";
	 $sql2 = "";
     if($dbwhere==""){
       if($m51_codordem!=null ){
         $sql2 .= " where matordem.m51_codordem = $m51_codordem ";
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
   function sql_query_item ( $m51_codordem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordem ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = matordem.m51_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matordem.m51_depto";
     $sql .= "      inner join matordemitem on matordemitem.m52_codordem = matordem.m51_codordem";
     $sql2 = "";
     if($dbwhere==""){
       if($m51_codordem!=null ){
         $sql2 .= " where matordem.m51_codordem = $m51_codordem "; 
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
   function sql_query_numemp ( $m51_codordem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matordem ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = matordem.m51_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matordem.m51_depto";
     $sql .= "      left join matordemanu on matordemanu.m53_codordem = matordem.m51_codordem";
     $sql .= "      inner join matordemitem on matordemitem.m52_codordem = matordem.m51_codordem";
     $sql .= "      inner join empempenho on empempenho.e60_numemp = matordemitem.m52_numemp";
     $sql2 = "";
     if($dbwhere==""){
       if($m51_codordem!=null ){
         $sql2 .= " where matordem.m51_codordem = $m51_codordem ";
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
   function sql_query_tot( $m51_codordem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matordem ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = matordem.m51_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matordem.m51_depto";
     $sql .= "      left join matordemanu  on  matordemanu.m53_codordem = matordem.m51_codordem";
     $sql2 = "";
     if($dbwhere==""){
       if($m51_codordem!=null ){
         $sql2 .= " where matordem.m51_codordem = $m51_codordem ";
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