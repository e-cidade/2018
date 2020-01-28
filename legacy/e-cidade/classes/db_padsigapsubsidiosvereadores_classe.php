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

//MODULO: Contabilidade
//CLASSE DA ENTIDADE padsigapsubsidiosvereadores
class cl_padsigapsubsidiosvereadores { 
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
   var $c16_sequencial = 0; 
   var $c16_mes = 0; 
   var $c16_ano = 0; 
   var $c16_numcgm = 0; 
   var $c16_instit = 0; 
   var $c16_subsidiomensal = 0; 
   var $c16_subsidioextraordinario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c16_sequencial = int4 = Código Sequencial 
                 c16_mes = int4 = Mês 
                 c16_ano = int4 = Ano 
                 c16_numcgm = int4 = Vereador 
                 c16_instit = int4 = Instituição 
                 c16_subsidiomensal = float8 = Valor do Subsídio mensal 
                 c16_subsidioextraordinario = float8 = Valor do Subsídio Extraordináro 
                 ";
   //funcao construtor da classe 
   function cl_padsigapsubsidiosvereadores() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("padsigapsubsidiosvereadores"); 
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
       $this->c16_sequencial = ($this->c16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c16_sequencial"]:$this->c16_sequencial);
       $this->c16_mes = ($this->c16_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c16_mes"]:$this->c16_mes);
       $this->c16_ano = ($this->c16_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c16_ano"]:$this->c16_ano);
       $this->c16_numcgm = ($this->c16_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["c16_numcgm"]:$this->c16_numcgm);
       $this->c16_instit = ($this->c16_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c16_instit"]:$this->c16_instit);
       $this->c16_subsidiomensal = ($this->c16_subsidiomensal == ""?@$GLOBALS["HTTP_POST_VARS"]["c16_subsidiomensal"]:$this->c16_subsidiomensal);
       $this->c16_subsidioextraordinario = ($this->c16_subsidioextraordinario == ""?@$GLOBALS["HTTP_POST_VARS"]["c16_subsidioextraordinario"]:$this->c16_subsidioextraordinario);
     }else{
       $this->c16_sequencial = ($this->c16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c16_sequencial"]:$this->c16_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c16_sequencial){ 
      $this->atualizacampos();
     if($this->c16_mes == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "c16_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c16_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "c16_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c16_numcgm == null ){ 
       $this->erro_sql = " Campo Vereador nao Informado.";
       $this->erro_campo = "c16_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c16_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "c16_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c16_subsidiomensal == null ){ 
       $this->erro_sql = " Campo Valor do Subsídio mensal nao Informado.";
       $this->erro_campo = "c16_subsidiomensal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c16_subsidioextraordinario == null ){ 
       $this->erro_sql = " Campo Valor do Subsídio Extraordináro nao Informado.";
       $this->erro_campo = "c16_subsidioextraordinario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c16_sequencial == "" || $c16_sequencial == null ){
       $result = db_query("select nextval('padsigapsubsidiosvereadores_c16_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: padsigapsubsidiosvereadores_c16_sequencial_seq do campo: c16_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c16_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from padsigapsubsidiosvereadores_c16_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c16_sequencial)){
         $this->erro_sql = " Campo c16_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c16_sequencial = $c16_sequencial; 
       }
     }
     if(($this->c16_sequencial == null) || ($this->c16_sequencial == "") ){ 
       $this->erro_sql = " Campo c16_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into padsigapsubsidiosvereadores(
                                       c16_sequencial 
                                      ,c16_mes 
                                      ,c16_ano 
                                      ,c16_numcgm 
                                      ,c16_instit 
                                      ,c16_subsidiomensal 
                                      ,c16_subsidioextraordinario 
                       )
                values (
                                $this->c16_sequencial 
                               ,$this->c16_mes 
                               ,$this->c16_ano 
                               ,$this->c16_numcgm 
                               ,$this->c16_instit 
                               ,$this->c16_subsidiomensal 
                               ,$this->c16_subsidioextraordinario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Subsidios para Vereadores ($this->c16_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Subsidios para Vereadores já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Subsidios para Vereadores ($this->c16_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c16_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c16_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16776,'$this->c16_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2954,16776,'','".AddSlashes(pg_result($resaco,0,'c16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2954,16777,'','".AddSlashes(pg_result($resaco,0,'c16_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2954,16778,'','".AddSlashes(pg_result($resaco,0,'c16_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2954,16779,'','".AddSlashes(pg_result($resaco,0,'c16_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2954,16780,'','".AddSlashes(pg_result($resaco,0,'c16_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2954,16781,'','".AddSlashes(pg_result($resaco,0,'c16_subsidiomensal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2954,16782,'','".AddSlashes(pg_result($resaco,0,'c16_subsidioextraordinario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c16_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update padsigapsubsidiosvereadores set ";
     $virgula = "";
     if(trim($this->c16_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c16_sequencial"])){ 
       $sql  .= $virgula." c16_sequencial = $this->c16_sequencial ";
       $virgula = ",";
       if(trim($this->c16_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "c16_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c16_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c16_mes"])){ 
       $sql  .= $virgula." c16_mes = $this->c16_mes ";
       $virgula = ",";
       if(trim($this->c16_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "c16_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c16_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c16_ano"])){ 
       $sql  .= $virgula." c16_ano = $this->c16_ano ";
       $virgula = ",";
       if(trim($this->c16_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "c16_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c16_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c16_numcgm"])){ 
       $sql  .= $virgula." c16_numcgm = $this->c16_numcgm ";
       $virgula = ",";
       if(trim($this->c16_numcgm) == null ){ 
         $this->erro_sql = " Campo Vereador nao Informado.";
         $this->erro_campo = "c16_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c16_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c16_instit"])){ 
       $sql  .= $virgula." c16_instit = $this->c16_instit ";
       $virgula = ",";
       if(trim($this->c16_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "c16_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c16_subsidiomensal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c16_subsidiomensal"])){ 
       $sql  .= $virgula." c16_subsidiomensal = $this->c16_subsidiomensal ";
       $virgula = ",";
       if(trim($this->c16_subsidiomensal) == null ){ 
         $this->erro_sql = " Campo Valor do Subsídio mensal nao Informado.";
         $this->erro_campo = "c16_subsidiomensal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c16_subsidioextraordinario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c16_subsidioextraordinario"])){ 
       $sql  .= $virgula." c16_subsidioextraordinario = $this->c16_subsidioextraordinario ";
       $virgula = ",";
       if(trim($this->c16_subsidioextraordinario) == null ){ 
         $this->erro_sql = " Campo Valor do Subsídio Extraordináro nao Informado.";
         $this->erro_campo = "c16_subsidioextraordinario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c16_sequencial!=null){
       $sql .= " c16_sequencial = $this->c16_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c16_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16776,'$this->c16_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c16_sequencial"]) || $this->c16_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2954,16776,'".AddSlashes(pg_result($resaco,$conresaco,'c16_sequencial'))."','$this->c16_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c16_mes"]) || $this->c16_mes != "")
           $resac = db_query("insert into db_acount values($acount,2954,16777,'".AddSlashes(pg_result($resaco,$conresaco,'c16_mes'))."','$this->c16_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c16_ano"]) || $this->c16_ano != "")
           $resac = db_query("insert into db_acount values($acount,2954,16778,'".AddSlashes(pg_result($resaco,$conresaco,'c16_ano'))."','$this->c16_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c16_numcgm"]) || $this->c16_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,2954,16779,'".AddSlashes(pg_result($resaco,$conresaco,'c16_numcgm'))."','$this->c16_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c16_instit"]) || $this->c16_instit != "")
           $resac = db_query("insert into db_acount values($acount,2954,16780,'".AddSlashes(pg_result($resaco,$conresaco,'c16_instit'))."','$this->c16_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c16_subsidiomensal"]) || $this->c16_subsidiomensal != "")
           $resac = db_query("insert into db_acount values($acount,2954,16781,'".AddSlashes(pg_result($resaco,$conresaco,'c16_subsidiomensal'))."','$this->c16_subsidiomensal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c16_subsidioextraordinario"]) || $this->c16_subsidioextraordinario != "")
           $resac = db_query("insert into db_acount values($acount,2954,16782,'".AddSlashes(pg_result($resaco,$conresaco,'c16_subsidioextraordinario'))."','$this->c16_subsidioextraordinario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Subsidios para Vereadores nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Subsidios para Vereadores nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c16_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c16_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16776,'$c16_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2954,16776,'','".AddSlashes(pg_result($resaco,$iresaco,'c16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2954,16777,'','".AddSlashes(pg_result($resaco,$iresaco,'c16_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2954,16778,'','".AddSlashes(pg_result($resaco,$iresaco,'c16_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2954,16779,'','".AddSlashes(pg_result($resaco,$iresaco,'c16_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2954,16780,'','".AddSlashes(pg_result($resaco,$iresaco,'c16_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2954,16781,'','".AddSlashes(pg_result($resaco,$iresaco,'c16_subsidiomensal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2954,16782,'','".AddSlashes(pg_result($resaco,$iresaco,'c16_subsidioextraordinario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from padsigapsubsidiosvereadores
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c16_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c16_sequencial = $c16_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Subsidios para Vereadores nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Subsidios para Vereadores nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c16_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:padsigapsubsidiosvereadores";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from padsigapsubsidiosvereadores ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = padsigapsubsidiosvereadores.c16_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($c16_sequencial!=null ){
         $sql2 .= " where padsigapsubsidiosvereadores.c16_sequencial = $c16_sequencial "; 
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
   function sql_query_file ( $c16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from padsigapsubsidiosvereadores ";
     $sql2 = "";
     if($dbwhere==""){
       if($c16_sequencial!=null ){
         $sql2 .= " where padsigapsubsidiosvereadores.c16_sequencial = $c16_sequencial "; 
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