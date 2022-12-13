<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: issqn
//CLASSE DA ENTIDADE issplan
class cl_issplan { 
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
   var $q20_planilha = 0; 
   var $q20_numcgm = 0; 
   var $q20_ano = 0; 
   var $q20_mes = 0; 
   var $q20_nomecontri = null; 
   var $q20_fonecontri = null; 
   var $q20_numpre = 0; 
   var $q20_numbco = 0; 
   var $q20_situacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q20_planilha = int4 = Código da Planilha 
                 q20_numcgm = int4 = Número do CGM 
                 q20_ano = int4 = Ano 
                 q20_mes = int4 = Mês 
                 q20_nomecontri = varchar(40) = Nome do contribuinte 
                 q20_fonecontri = varchar(40) = Telefone do Contribuinte 
                 q20_numpre = int8 = Numpre 
                 q20_numbco = int8 = Número do Banco 
                 q20_situacao = int4 = situaçao 
                 ";
   //funcao construtor da classe 
   function cl_issplan() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issplan"); 
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
       $this->q20_planilha = ($this->q20_planilha == ""?@$GLOBALS["HTTP_POST_VARS"]["q20_planilha"]:$this->q20_planilha);
       $this->q20_numcgm = ($this->q20_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["q20_numcgm"]:$this->q20_numcgm);
       $this->q20_ano = ($this->q20_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q20_ano"]:$this->q20_ano);
       $this->q20_mes = ($this->q20_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q20_mes"]:$this->q20_mes);
       $this->q20_nomecontri = ($this->q20_nomecontri == ""?@$GLOBALS["HTTP_POST_VARS"]["q20_nomecontri"]:$this->q20_nomecontri);
       $this->q20_fonecontri = ($this->q20_fonecontri == ""?@$GLOBALS["HTTP_POST_VARS"]["q20_fonecontri"]:$this->q20_fonecontri);
       $this->q20_numpre = ($this->q20_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["q20_numpre"]:$this->q20_numpre);
       $this->q20_numbco = ($this->q20_numbco == ""?@$GLOBALS["HTTP_POST_VARS"]["q20_numbco"]:$this->q20_numbco);
       $this->q20_situacao = ($this->q20_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["q20_situacao"]:$this->q20_situacao);
     }else{
       $this->q20_planilha = ($this->q20_planilha == ""?@$GLOBALS["HTTP_POST_VARS"]["q20_planilha"]:$this->q20_planilha);
     }
   }
   // funcao para inclusao
   function incluir ($q20_planilha){ 
      $this->atualizacampos();
     if($this->q20_numcgm == null ){ 
       $this->erro_sql = " Campo Número do CGM nao Informado.";
       $this->erro_campo = "q20_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q20_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "q20_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q20_mes == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "q20_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q20_numpre == null ){ 
       $this->q20_numpre = "0";
     }
     if($this->q20_numbco == null ){ 
       $this->q20_numbco = "0";
     }
     if($this->q20_situacao == null ){ 
       $this->q20_situacao = "0";
     }
     if($q20_planilha == "" || $q20_planilha == null ){
       $result = db_query("select nextval('issplan_q20_planilha_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issplan_q20_planilha_seq do campo: q20_planilha"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q20_planilha = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issplan_q20_planilha_seq");
       if(($result != false) && (pg_result($result,0,0) < $q20_planilha)){
         $this->erro_sql = " Campo q20_planilha maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q20_planilha = $q20_planilha; 
       }
     }
     if(($this->q20_planilha == null) || ($this->q20_planilha == "") ){ 
       $this->erro_sql = " Campo q20_planilha nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issplan(
                                       q20_planilha 
                                      ,q20_numcgm 
                                      ,q20_ano 
                                      ,q20_mes 
                                      ,q20_nomecontri 
                                      ,q20_fonecontri 
                                      ,q20_numpre 
                                      ,q20_numbco 
                                      ,q20_situacao 
                       )
                values (
                                $this->q20_planilha 
                               ,$this->q20_numcgm 
                               ,$this->q20_ano 
                               ,$this->q20_mes 
                               ,'$this->q20_nomecontri' 
                               ,'$this->q20_fonecontri' 
                               ,$this->q20_numpre 
                               ,$this->q20_numbco 
                               ,$this->q20_situacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Planilha de retenção na fonte ($this->q20_planilha) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Planilha de retenção na fonte já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Planilha de retenção na fonte ($this->q20_planilha) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q20_planilha;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q20_planilha));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2586,'$this->q20_planilha','I')");
       $resac = db_query("insert into db_acount values($acount,421,2586,'','".AddSlashes(pg_result($resaco,0,'q20_planilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,421,2587,'','".AddSlashes(pg_result($resaco,0,'q20_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,421,2589,'','".AddSlashes(pg_result($resaco,0,'q20_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,421,2590,'','".AddSlashes(pg_result($resaco,0,'q20_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,421,2591,'','".AddSlashes(pg_result($resaco,0,'q20_nomecontri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,421,2592,'','".AddSlashes(pg_result($resaco,0,'q20_fonecontri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,421,2593,'','".AddSlashes(pg_result($resaco,0,'q20_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,421,2594,'','".AddSlashes(pg_result($resaco,0,'q20_numbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,421,9210,'','".AddSlashes(pg_result($resaco,0,'q20_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q20_planilha=null) { 
      $this->atualizacampos();
     $sql = " update issplan set ";
     $virgula = "";
     if(trim($this->q20_planilha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q20_planilha"])){ 
       $sql  .= $virgula." q20_planilha = $this->q20_planilha ";
       $virgula = ",";
       if(trim($this->q20_planilha) == null ){ 
         $this->erro_sql = " Campo Código da Planilha nao Informado.";
         $this->erro_campo = "q20_planilha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q20_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q20_numcgm"])){ 
       $sql  .= $virgula." q20_numcgm = $this->q20_numcgm ";
       $virgula = ",";
       if(trim($this->q20_numcgm) == null ){ 
         $this->erro_sql = " Campo Número do CGM nao Informado.";
         $this->erro_campo = "q20_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q20_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q20_ano"])){ 
       $sql  .= $virgula." q20_ano = $this->q20_ano ";
       $virgula = ",";
       if(trim($this->q20_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "q20_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q20_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q20_mes"])){ 
       $sql  .= $virgula." q20_mes = $this->q20_mes ";
       $virgula = ",";
       if(trim($this->q20_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "q20_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q20_nomecontri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q20_nomecontri"])){ 
       $sql  .= $virgula." q20_nomecontri = '$this->q20_nomecontri' ";
       $virgula = ",";
     }
     if(trim($this->q20_fonecontri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q20_fonecontri"])){ 
       $sql  .= $virgula." q20_fonecontri = '$this->q20_fonecontri' ";
       $virgula = ",";
     }
     if(trim($this->q20_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q20_numpre"])){ 
        if(trim($this->q20_numpre)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q20_numpre"])){ 
           $this->q20_numpre = "0" ; 
        } 
       $sql  .= $virgula." q20_numpre = $this->q20_numpre ";
       $virgula = ",";
     }
     if(trim($this->q20_numbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q20_numbco"])){ 
        if(trim($this->q20_numbco)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q20_numbco"])){ 
           $this->q20_numbco = "0" ; 
        } 
       $sql  .= $virgula." q20_numbco = $this->q20_numbco ";
       $virgula = ",";
     }
     if(trim($this->q20_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q20_situacao"])){ 
        if(trim($this->q20_situacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q20_situacao"])){ 
           $this->q20_situacao = "0" ; 
        } 
       $sql  .= $virgula." q20_situacao = $this->q20_situacao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q20_planilha!=null){
       $sql .= " q20_planilha = $this->q20_planilha";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q20_planilha));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2586,'$this->q20_planilha','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q20_planilha"]))
           $resac = db_query("insert into db_acount values($acount,421,2586,'".AddSlashes(pg_result($resaco,$conresaco,'q20_planilha'))."','$this->q20_planilha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q20_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,421,2587,'".AddSlashes(pg_result($resaco,$conresaco,'q20_numcgm'))."','$this->q20_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q20_ano"]))
           $resac = db_query("insert into db_acount values($acount,421,2589,'".AddSlashes(pg_result($resaco,$conresaco,'q20_ano'))."','$this->q20_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q20_mes"]))
           $resac = db_query("insert into db_acount values($acount,421,2590,'".AddSlashes(pg_result($resaco,$conresaco,'q20_mes'))."','$this->q20_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q20_nomecontri"]))
           $resac = db_query("insert into db_acount values($acount,421,2591,'".AddSlashes(pg_result($resaco,$conresaco,'q20_nomecontri'))."','$this->q20_nomecontri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q20_fonecontri"]))
           $resac = db_query("insert into db_acount values($acount,421,2592,'".AddSlashes(pg_result($resaco,$conresaco,'q20_fonecontri'))."','$this->q20_fonecontri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q20_numpre"]))
           $resac = db_query("insert into db_acount values($acount,421,2593,'".AddSlashes(pg_result($resaco,$conresaco,'q20_numpre'))."','$this->q20_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q20_numbco"]))
           $resac = db_query("insert into db_acount values($acount,421,2594,'".AddSlashes(pg_result($resaco,$conresaco,'q20_numbco'))."','$this->q20_numbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q20_situacao"]))
           $resac = db_query("insert into db_acount values($acount,421,9210,'".AddSlashes(pg_result($resaco,$conresaco,'q20_situacao'))."','$this->q20_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Planilha de retenção na fonte nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q20_planilha;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Planilha de retenção na fonte nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q20_planilha;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q20_planilha;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q20_planilha=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q20_planilha));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2586,'$q20_planilha','E')");
         $resac = db_query("insert into db_acount values($acount,421,2586,'','".AddSlashes(pg_result($resaco,$iresaco,'q20_planilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,421,2587,'','".AddSlashes(pg_result($resaco,$iresaco,'q20_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,421,2589,'','".AddSlashes(pg_result($resaco,$iresaco,'q20_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,421,2590,'','".AddSlashes(pg_result($resaco,$iresaco,'q20_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,421,2591,'','".AddSlashes(pg_result($resaco,$iresaco,'q20_nomecontri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,421,2592,'','".AddSlashes(pg_result($resaco,$iresaco,'q20_fonecontri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,421,2593,'','".AddSlashes(pg_result($resaco,$iresaco,'q20_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,421,2594,'','".AddSlashes(pg_result($resaco,$iresaco,'q20_numbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,421,9210,'','".AddSlashes(pg_result($resaco,$iresaco,'q20_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issplan
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q20_planilha != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q20_planilha = $q20_planilha ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Planilha de retenção na fonte nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q20_planilha;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Planilha de retenção na fonte nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q20_planilha;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q20_planilha;
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
        $this->erro_sql   = "Record Vazio na Tabela:issplan";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q20_planilha=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issplan ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = issplan.q20_inscr";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issplan.q20_numcgm";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = issbase.q02_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q20_planilha!=null ){
         $sql2 .= " where issplan.q20_planilha = $q20_planilha "; 
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
   function sql_query_arrecad ( $q20_planilha=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from issplan ";
     $sql.= "       left join issplaninscr on q20_planilha = q24_planilha  ";
     $sql.= "       left outer join arrecad   on  issplan.q20_numpre = arrecad.k00_numpre  ";
     $sql.= "       left outer join arrepaga  on  issplan.q20_numpre = arrepaga.k00_numpre ";
     $sql2 = "";
     if($dbwhere==""){
       if($q20_planilha!=null ){
         $sql2 .= " where issplan.q20_planilha = $q20_planilha ";
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
   function sql_query_file ( $q20_planilha=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issplan ";
     $sql2 = "";
     if($dbwhere==""){
       if($q20_planilha!=null ){
         $sql2 .= " where issplan.q20_planilha = $q20_planilha "; 
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
	
  /**
   * Busca informacoes da planilha
   * 
   * @param integer $q20_planilha
   * @param string $campos
   * @return string
   */
	function sql_query_issplaninscr ($q20_planilha, $campos = "*") {
	 	
	 	$sql = "select ";
	 	if ( $campos != "*") {
	 		
	 		$campos_sql = split("#",$campos);
	 		$virgula = "";
	 		for($i=0;$i<sizeof($campos_sql);$i++){
	 			$sql .= $virgula.$campos_sql[$i];
	 			$virgula = ",";
	 		}
	 	} else { 
	 		$sql .= $campos;
	 	}
	 	
	 	$sql .= "  from issplan";
	 	$sql .= "     	left join issplaninscr   on q24_planilha = q20_planilha";
	 	$sql .= "       left join issplannumpre  on q32_planilha = q20_planilha";
	 	$sql .= " where issplan.q20_planilha = {$q20_planilha}";

	 	return $sql;
	 }

}
?>