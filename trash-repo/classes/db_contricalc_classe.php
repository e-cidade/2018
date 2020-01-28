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

//MODULO: contrib
//CLASSE DA ENTIDADE contricalc
class cl_contricalc { 
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
   var $d09_sequencial = 0; 
   var $d09_contri = 0; 
   var $d09_matric = 0; 
   var $d09_numpre = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d09_sequencial = int4 = Codigo 
                 d09_contri = int4 = Contribuicao 
                 d09_matric = int4 = Matricula 
                 d09_numpre = int4 = Numpre 
                 ";
   //funcao construtor da classe 
   function cl_contricalc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("contricalc"); 
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
       $this->d09_sequencial = ($this->d09_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_sequencial"]:$this->d09_sequencial);
       $this->d09_contri = ($this->d09_contri == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_contri"]:$this->d09_contri);
       $this->d09_matric = ($this->d09_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_matric"]:$this->d09_matric);
       $this->d09_numpre = ($this->d09_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_numpre"]:$this->d09_numpre);
     }else{
       $this->d09_sequencial = ($this->d09_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["d09_sequencial"]:$this->d09_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($d09_sequencial){ 
      $this->atualizacampos();
     if($this->d09_contri == null ){ 
       $this->erro_sql = " Campo Contribuicao nao Informado.";
       $this->erro_campo = "d09_contri";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_matric == null ){ 
       $this->erro_sql = " Campo Matricula nao Informado.";
       $this->erro_campo = "d09_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d09_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "d09_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->d09_sequencial = $d09_sequencial; 
     if(($this->d09_sequencial == null) || ($this->d09_sequencial == "") ){ 
       $this->erro_sql = " Campo d09_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into contricalc(
                                       d09_sequencial 
                                      ,d09_contri 
                                      ,d09_matric 
                                      ,d09_numpre 
                       )
                values (
                                $this->d09_sequencial 
                               ,$this->d09_contri 
                               ,$this->d09_matric 
                               ,$this->d09_numpre 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->d09_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->d09_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d09_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d09_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10180,'$this->d09_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,134,10180,'','".AddSlashes(pg_result($resaco,0,'d09_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,134,719,'','".AddSlashes(pg_result($resaco,0,'d09_contri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,134,720,'','".AddSlashes(pg_result($resaco,0,'d09_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,134,721,'','".AddSlashes(pg_result($resaco,0,'d09_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d09_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update contricalc set ";
     $virgula = "";
     if(trim($this->d09_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_sequencial"])){ 
       $sql  .= $virgula." d09_sequencial = $this->d09_sequencial ";
       $virgula = ",";
       if(trim($this->d09_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "d09_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_contri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_contri"])){ 
       $sql  .= $virgula." d09_contri = $this->d09_contri ";
       $virgula = ",";
       if(trim($this->d09_contri) == null ){ 
         $this->erro_sql = " Campo Contribuicao nao Informado.";
         $this->erro_campo = "d09_contri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_matric"])){ 
       $sql  .= $virgula." d09_matric = $this->d09_matric ";
       $virgula = ",";
       if(trim($this->d09_matric) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "d09_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d09_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d09_numpre"])){ 
       $sql  .= $virgula." d09_numpre = $this->d09_numpre ";
       $virgula = ",";
       if(trim($this->d09_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "d09_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($d09_sequencial!=null){
       $sql .= " d09_sequencial = $this->d09_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d09_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10180,'$this->d09_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d09_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,134,10180,'".AddSlashes(pg_result($resaco,$conresaco,'d09_sequencial'))."','$this->d09_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d09_contri"]))
           $resac = db_query("insert into db_acount values($acount,134,719,'".AddSlashes(pg_result($resaco,$conresaco,'d09_contri'))."','$this->d09_contri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d09_matric"]))
           $resac = db_query("insert into db_acount values($acount,134,720,'".AddSlashes(pg_result($resaco,$conresaco,'d09_matric'))."','$this->d09_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d09_numpre"]))
           $resac = db_query("insert into db_acount values($acount,134,721,'".AddSlashes(pg_result($resaco,$conresaco,'d09_numpre'))."','$this->d09_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d09_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d09_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d09_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10180,'$d09_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,134,10180,'','".AddSlashes(pg_result($resaco,$iresaco,'d09_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,134,719,'','".AddSlashes(pg_result($resaco,$iresaco,'d09_contri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,134,720,'','".AddSlashes(pg_result($resaco,$iresaco,'d09_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,134,721,'','".AddSlashes(pg_result($resaco,$iresaco,'d09_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from contricalc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d09_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d09_sequencial = $d09_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d09_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d09_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:contricalc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function excluir_arrecad($numpre,$numpar=0){
    $sql = "select *
            from arrecad
            where k00_numpre=$numpre";
    if($numpar>0){
      $sql .= " and k00_numpar = $numpar";
    }
    $result=@pg_query($sql);

    if($result!=false && pg_numrows($result)>0){
      $k00_numpre = pg_result($result,0,"k00_numpre");
      $k00_numpar = pg_result($result,0,"k00_numpar");
      $k00_numcgm = pg_result($result,0,"k00_numcgm");
      $k00_dtoper = pg_result($result,0,"k00_dtoper");
      $k00_receit = pg_result($result,0,"k00_receit");
      $k00_hist = pg_result($result,0,"k00_hist");
      $k00_valor = pg_result($result,0,"k00_valor");
      $k00_dtvenc = pg_result($result,0,"k00_dtvenc");
      $d00_numtot = pg_result($result,0,"k00_numtot");
      $k00_numdig = pg_result($result,0,"k00_numdig");
      $k00_tipo = pg_result($result,0,"k00_tipo");
      $k00_tipojm = pg_result($result,0,"k00_tipojm");
      for($a=0; $a<pg_numrows($result); $a++){
        $result = @pg_query("insert into arreold(
                                       k00_numpre
                                      ,k00_numpar
                                      ,k00_numcgm
                                      ,k00_dtoper
                                      ,k00_receit
                                      ,k00_hist
                                      ,k00_valor
                                      ,k00_dtvenc
                                      ,k00_numtot
   ,k00_numdig
                                      ,k00_tipo
                                      ,k00_tipojm
                       )
                values (
                                       $k00_numpre
                                      ,$k00_numpar
                                      ,$k00_numcgm
                                      ,'$k00_dtoper'
                                      ,$k00_receit
                                      ,$k00_hist
                                      ,$k00_valor
                                      ,'$k00_dtvenc'
                                      ,$d00_numtot
                                      ,$k00_numdig
                                      ,$k00_tipo
                                      ,$k00_tipojm
                      )");
         if($result==false){
            $this->erro_status="0";
            $this->erro_msg="Erro ao incluir em Arreold";
            return false;
         }
       }
       $sql = "delete from arrecad where k00_numpre=$k00_numpre";
       if($numpar>0){
	 $sql .= " and k00_numpar = $numpar";
       }
       $result=@pg_query($sql);
       if($result==false){
	  $this->erro_status="0";
	  $this->erro_msg="Erro ao excluir em Arrecad";
	  return false;
	}else{
	  $this->erro_status="1";
	  $this->erro_msg="Exclusão efetivada com sucesso!";
	  return true;
       }
     }
  }
   function fc_calculocontr($d09_contri,$d09_matric,$parcelas,$privenc_ano,$privenc_mes,$privenc_dia,$provenc){

     $this->d09_contri = $d09_contri;
     $this->d09_matric = $d09_matric;
     if(($this->d09_contri == null) || ($this->d09_contri == "") ){
       $this->erro_sql = " Campo d09_contri nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if(($this->d09_matric == null) || ($this->d09_matric == "") ){
       $this->erro_sql = " Campo d09_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     
//    echo " SELECT FC_CALCULOCONTR('$d09_contri','$d09_matric','$parcelas','$privenc_ano-$privenc_mes-$privenc_dia','$provenc') AS CALCULO <br><br>";
     $result = pg_query(" SELECT FC_CALCULOCONTR('$d09_contri','$d09_matric','$parcelas','$privenc_ano-$privenc_mes-$privenc_dia','$provenc') AS CALCULO");

     if($result==false){
       $this->erro_banco = str_replace("\n","",pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->d09_contri."-".$this->d09_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->d09_contri."-".$this->d09_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }

     $this->erro_banco = "";
     $this->erro_sql = "Inclusao Efetivada com Sucesso\\n";
     $this->erro_sql .= "Valores : ".$this->d09_contri."-".$this->d09_matric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";

/*     $resaco = $this->sql_record($this->sql_query_file($this->d09_contri,$this->d09_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac  = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac  = pg_query("insert into db_acountkey values($acount,719,'$this->d09_contri','I')");
       $resac  = pg_query("insert into db_acountkey values($acount,720,'$this->d09_matric','I')");
       $resac  = pg_query("insert into db_acount values($acount,134,719,'','".pg_result($resaco,0,'d09_contri')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac  = pg_query("insert into db_acount values($acount,134,720,'','".pg_result($resaco,0,'d09_matric')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
		 }*/
   $this->erro_msg = "Processamento concluido com sucesso !";
   $this->erro_status = "1";
   return true;
 }
   function sql_query ( $d09_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contricalc ";
     $sql .= "      inner join contrib  on  contrib.d07_contri = contricalc.d09_contri and  contrib.d07_matric = contricalc.d09_matric";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = contrib.d07_matric";
     $sql .= "      inner join contlot  on  contlot.d05_contri = contrib.d07_contri and  contlot.d05_idbql = contrib.d07_idbql";
     $sql .= "      inner join iptubase  as a on   a.j01_matric = contrib.d07_matric";
     $sql .= "      inner join contlot  as b on   b.d05_contri = contrib.d07_contri and   b.d05_idbql = contrib.d07_idbql";
     $sql2 = "";
     if($dbwhere==""){
       if($d09_sequencial!=null ){
         $sql2 .= " where contricalc.d09_sequencial = $d09_sequencial "; 
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
   function sql_query_file ( $d09_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contricalc ";
     $sql2 = "";
     if($dbwhere==""){
       if($d09_sequencial!=null ){
         $sql2 .= " where contricalc.d09_sequencial = $d09_sequencial "; 
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