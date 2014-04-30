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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_virada
class cl_db_virada { 
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
   var $c30_sequencial = 0; 
   var $c30_anoorigem = 0; 
   var $c30_anodestino = 0; 
   var $c30_usuario = 0; 
   var $c30_data_dia = null; 
   var $c30_data_mes = null; 
   var $c30_data_ano = null; 
   var $c30_data = null; 
   var $c30_hora = null; 
   var $c30_situacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c30_sequencial = int4 = Codigo 
                 c30_anoorigem = int4 = Ano origem 
                 c30_anodestino = int4 = Ano destino 
                 c30_usuario = int4 = Usuário 
                 c30_data = date = Data 
                 c30_hora = char(5) = Hora 
                 c30_situacao = int4 = Situação 
                 ";
   //funcao construtor da classe 
   function cl_db_virada() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_virada"); 
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
       $this->c30_sequencial = ($this->c30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c30_sequencial"]:$this->c30_sequencial);
       $this->c30_anoorigem = ($this->c30_anoorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["c30_anoorigem"]:$this->c30_anoorigem);
       $this->c30_anodestino = ($this->c30_anodestino == ""?@$GLOBALS["HTTP_POST_VARS"]["c30_anodestino"]:$this->c30_anodestino);
       $this->c30_usuario = ($this->c30_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["c30_usuario"]:$this->c30_usuario);
       if($this->c30_data == ""){
         $this->c30_data_dia = ($this->c30_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c30_data_dia"]:$this->c30_data_dia);
         $this->c30_data_mes = ($this->c30_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c30_data_mes"]:$this->c30_data_mes);
         $this->c30_data_ano = ($this->c30_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c30_data_ano"]:$this->c30_data_ano);
         if($this->c30_data_dia != ""){
            $this->c30_data = $this->c30_data_ano."-".$this->c30_data_mes."-".$this->c30_data_dia;
         }
       }
       $this->c30_hora = ($this->c30_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["c30_hora"]:$this->c30_hora);
       $this->c30_situacao = ($this->c30_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["c30_situacao"]:$this->c30_situacao);
     }else{
       $this->c30_sequencial = ($this->c30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c30_sequencial"]:$this->c30_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c30_sequencial){ 
      $this->atualizacampos();
     if($this->c30_anoorigem == null ){ 
       $this->erro_sql = " Campo Ano origem nao Informado.";
       $this->erro_campo = "c30_anoorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c30_anodestino == null ){ 
       $this->erro_sql = " Campo Ano destino nao Informado.";
       $this->erro_campo = "c30_anodestino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c30_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "c30_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c30_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "c30_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c30_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "c30_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c30_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "c30_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c30_sequencial == "" || $c30_sequencial == null ){
       $result = db_query("select nextval('db_virada_c30_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_virada_c30_sequencial_seq do campo: c30_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c30_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_virada_c30_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c30_sequencial)){
         $this->erro_sql = " Campo c30_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c30_sequencial = $c30_sequencial; 
       }
     }
     if(($this->c30_sequencial == null) || ($this->c30_sequencial == "") ){ 
       $this->erro_sql = " Campo c30_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_virada(
                                       c30_sequencial 
                                      ,c30_anoorigem 
                                      ,c30_anodestino 
                                      ,c30_usuario 
                                      ,c30_data 
                                      ,c30_hora 
                                      ,c30_situacao 
                       )
                values (
                                $this->c30_sequencial 
                               ,$this->c30_anoorigem 
                               ,$this->c30_anodestino 
                               ,$this->c30_usuario 
                               ,".($this->c30_data == "null" || $this->c30_data == ""?"null":"'".$this->c30_data."'")." 
                               ,'$this->c30_hora' 
                               ,$this->c30_situacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Virada ($this->c30_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Virada já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Virada ($this->c30_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c30_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c30_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10789,'$this->c30_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1857,10789,'','".AddSlashes(pg_result($resaco,0,'c30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1857,10790,'','".AddSlashes(pg_result($resaco,0,'c30_anoorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1857,10791,'','".AddSlashes(pg_result($resaco,0,'c30_anodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1857,10792,'','".AddSlashes(pg_result($resaco,0,'c30_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1857,10793,'','".AddSlashes(pg_result($resaco,0,'c30_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1857,10795,'','".AddSlashes(pg_result($resaco,0,'c30_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1857,10796,'','".AddSlashes(pg_result($resaco,0,'c30_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c30_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_virada set ";
     $virgula = "";
     if(trim($this->c30_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c30_sequencial"])){ 
       $sql  .= $virgula." c30_sequencial = $this->c30_sequencial ";
       $virgula = ",";
       if(trim($this->c30_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "c30_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c30_anoorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c30_anoorigem"])){ 
       $sql  .= $virgula." c30_anoorigem = $this->c30_anoorigem ";
       $virgula = ",";
       if(trim($this->c30_anoorigem) == null ){ 
         $this->erro_sql = " Campo Ano origem nao Informado.";
         $this->erro_campo = "c30_anoorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c30_anodestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c30_anodestino"])){ 
       $sql  .= $virgula." c30_anodestino = $this->c30_anodestino ";
       $virgula = ",";
       if(trim($this->c30_anodestino) == null ){ 
         $this->erro_sql = " Campo Ano destino nao Informado.";
         $this->erro_campo = "c30_anodestino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c30_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c30_usuario"])){ 
       $sql  .= $virgula." c30_usuario = $this->c30_usuario ";
       $virgula = ",";
       if(trim($this->c30_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "c30_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c30_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c30_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c30_data_dia"] !="") ){ 
       $sql  .= $virgula." c30_data = '$this->c30_data' ";
       $virgula = ",";
       if(trim($this->c30_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "c30_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c30_data_dia"])){ 
         $sql  .= $virgula." c30_data = null ";
         $virgula = ",";
         if(trim($this->c30_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "c30_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c30_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c30_hora"])){ 
       $sql  .= $virgula." c30_hora = '$this->c30_hora' ";
       $virgula = ",";
       if(trim($this->c30_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "c30_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c30_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c30_situacao"])){ 
       $sql  .= $virgula." c30_situacao = $this->c30_situacao ";
       $virgula = ",";
       if(trim($this->c30_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "c30_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c30_sequencial!=null){
       $sql .= " c30_sequencial = $this->c30_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c30_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10789,'$this->c30_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c30_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1857,10789,'".AddSlashes(pg_result($resaco,$conresaco,'c30_sequencial'))."','$this->c30_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c30_anoorigem"]))
           $resac = db_query("insert into db_acount values($acount,1857,10790,'".AddSlashes(pg_result($resaco,$conresaco,'c30_anoorigem'))."','$this->c30_anoorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c30_anodestino"]))
           $resac = db_query("insert into db_acount values($acount,1857,10791,'".AddSlashes(pg_result($resaco,$conresaco,'c30_anodestino'))."','$this->c30_anodestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c30_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1857,10792,'".AddSlashes(pg_result($resaco,$conresaco,'c30_usuario'))."','$this->c30_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c30_data"]))
           $resac = db_query("insert into db_acount values($acount,1857,10793,'".AddSlashes(pg_result($resaco,$conresaco,'c30_data'))."','$this->c30_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c30_hora"]))
           $resac = db_query("insert into db_acount values($acount,1857,10795,'".AddSlashes(pg_result($resaco,$conresaco,'c30_hora'))."','$this->c30_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c30_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1857,10796,'".AddSlashes(pg_result($resaco,$conresaco,'c30_situacao'))."','$this->c30_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Virada nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c30_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Virada nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c30_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c30_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10789,'$c30_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1857,10789,'','".AddSlashes(pg_result($resaco,$iresaco,'c30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1857,10790,'','".AddSlashes(pg_result($resaco,$iresaco,'c30_anoorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1857,10791,'','".AddSlashes(pg_result($resaco,$iresaco,'c30_anodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1857,10792,'','".AddSlashes(pg_result($resaco,$iresaco,'c30_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1857,10793,'','".AddSlashes(pg_result($resaco,$iresaco,'c30_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1857,10795,'','".AddSlashes(pg_result($resaco,$iresaco,'c30_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1857,10796,'','".AddSlashes(pg_result($resaco,$iresaco,'c30_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_virada
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c30_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c30_sequencial = $c30_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Virada nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c30_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Virada nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c30_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_virada";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c30_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_virada ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_virada.c30_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($c30_sequencial!=null ){
         $sql2 .= " where db_virada.c30_sequencial = $c30_sequencial "; 
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
   function sql_query_file ( $c30_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_virada ";
     $sql2 = "";
     if($dbwhere==""){
       if($c30_sequencial!=null ){
         $sql2 .= " where db_virada.c30_sequencial = $c30_sequencial "; 
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